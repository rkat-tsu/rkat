<?php

namespace App\Http\Controllers;

use App\Models\RkatHeader;
use App\Models\LogPersetujuan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Illuminate\Http\RedirectResponse;
use Inertia\Response;
use Illuminate\Support\Facades\Redirect; // Import Redirect

class ApprovalController extends Controller
{
    // Peta peran ke status persetujuan yang mereka tunggu
    protected $roleStatusMap = [
        'Dekan' => 'Menunggu_Dekan_Kepala',
        'Kepala_Unit' => 'Menunggu_Dekan_Kepala',
        'WR_1' => 'Menunggu_WR1',
        'WR_2' => 'Menunggu_WR2',
        'WR_3' => 'Menunggu_WR3',
    ];

    /**
     * Menampilkan daftar RKAT yang perlu disetujui oleh pengguna saat ini.
     */
    public function index(Request $request): Response
    {
        $user = $request->user();
        $peran = $user->peran;

        // Otorisasi: Hanya approver dan admin yang bisa melihat
        if (!$user->isApprover() && !$user->isAdmin()) {
            return Inertia::render('Error', ['status' => 403, 'message' => 'Anda tidak memiliki hak akses untuk halaman ini.']);
        }

        // Load relasi unit beserta jalur_persetujuan-nya
        $query = RkatHeader::with(['unit:id_unit,nama_unit,jalur_persetujuan', 'programKerja:id_proker,nama_proker']);

        $targetStatus = $this->roleStatusMap[$peran] ?? null;

        // LOGIKA FILTER BERDASARKAN PERAN DAN UNIT
        if ($targetStatus) {
            $query->where('status_persetujuan', $targetStatus);
            
            // Logika Dekan/Kepala Unit: Hanya tampilkan RKAT dari unit yang mereka pimpin atau di bawahnya
            if ($user->isUnitHead()) {
                // *** PERBAIKAN: Tambahkan null check untuk $user->unit ***
                if ($user->unit) { 
                    $childUnitIds = $user->unit->children->pluck('id_unit')->toArray();
                    $unitAksesIds = array_merge([$user->id_unit], $childUnitIds);
                    $query->whereIn('id_unit', $unitAksesIds);
                } else {
                    // Jika Dekan/Kepala Unit tidak terhubung ke Unit, jangan tampilkan apa-apa
                    $query->whereRaw('1 = 0'); 
                }
            }
        } else if ($peran === 'Admin' || $peran === 'Rektor') {
            // Admin/Rektor melihat semua yang sedang dalam proses persetujuan (tidak termasuk Draft, Revisi, Ditolak, Final)
            $validStatuses = array_values($this->roleStatusMap);
            $query->whereIn('status_persetujuan', $validStatuses);
        } else {
            // Peran approver lain (seperti Rektor yang tidak ada di roleStatusMap)
            $query->whereRaw('1 = 0');
        }

        $rkatList = $query->latest('updated_at')->get();

        return Inertia::render('Approval/Index', [
            'rkatMenunggu' => $rkatList,
            'currentRole' => $peran,
        ]);
    }

    /**
     * Menyimpan aksi persetujuan/revisi/tolak.
     */
    public function approve(Request $request, RkatHeader $rkatHeader): RedirectResponse
    {
        $user = $request->user();
        
        // 1. Validasi Input
        $request->validate([
            'aksi' => ['required', 'string', Rule::in(['Setuju', 'Revisi', 'Tolak'])],
            'catatan' => 'nullable|string|max:1000',
        ]);

        // Cek status saat ini dan pastikan pengguna berhak menyetujui di level ini
        $currentStatus = $rkatHeader->status_persetujuan;
        $expectedStatus = $this->roleStatusMap[$user->peran] ?? null;
        
        if ($currentStatus !== $expectedStatus) {
             return Redirect::back()->withErrors(['error' => 'RKAT ini tidak berada pada status persetujuan Anda atau statusnya telah berubah.']);
        }

        $aksi = $request->aksi;
        
        DB::transaction(function () use ($rkatHeader, $user, $aksi, $request) {
            
            $newStatus = $rkatHeader->status_persetujuan;

            if ($aksi === 'Setuju') {
                // Tentukan status berikutnya secara dinamis
                $newStatus = $this->getNextStatus($rkatHeader);
            } elseif ($aksi === 'Revisi') {
                $newStatus = 'Revisi';
            } elseif ($aksi === 'Tolak') {
                $newStatus = 'Ditolak';
            }

            // 3. Update Status RKAT Header
            $rkatHeader->status_persetujuan = $newStatus;
            $rkatHeader->save();

            // 4. Catat Log Persetujuan
            LogPersetujuan::create([
                'id_header' => $rkatHeader->id_header,
                'id_approver' => $user->id_user,
                'level_persetujuan' => $user->peran,
                'aksi' => $aksi,
                'catatan' => $request->catatan,
            ]);
        });

        return Redirect::route('approval.index')->with('success', "RKAT #{$rkatHeader->id_header} berhasil di{$aksi}.");
    }

    /**
     * FUNGSI DINAMIS: Menentukan status persetujuan berikutnya.
     */
    protected function getNextStatus(RkatHeader $rkat): string
    {
        $currentStatus = $rkat->status_persetujuan;
        
        // Load ulang relasi unit jika belum di-load
        $rkat->loadMissing('unit'); 
        
        // Periksa apakah unit ada
        if (!$rkat->unit) {
            return $currentStatus; // Kembalikan status saat ini jika unit tidak ditemukan
        }
        
        // Ambil jalur dari relasi unit
        $jalur = $rkat->unit->jalur_persetujuan ?? 'akademik'; // Default ke 'akademik'

        // Definisikan semua alur persetujuan
        // Urutan: WR1 (Akademik), WR3 (Kampus/Umum), WR2 (Dana)
        $flows = [
            // Jalur Akademik (Fakultas, Prodi, Kemahasiswaan)
            'akademik' => [
                'Menunggu_Dekan_Kepala' => 'Menunggu_WR1', // L1 -> WR1 (Akademik)
                'Menunggu_WR1' => 'Menunggu_WR3',          // WR1 -> WR3 (Umum)
                'Menunggu_WR3' => 'Menunggu_WR2',          // WR3 -> WR2 (Dana)
                'Menunggu_WR2' => 'Disetujui_Final',       // WR2 -> Final
            ],
            // Jalur Non-Akademik (CDC, dll.)
            'non_akademik' => [
                'Menunggu_Dekan_Kepala' => 'Menunggu_WR3', // L1 -> WR3 (Umum)
                'Menunggu_WR3' => 'Menunggu_WR1',          // WR3 -> WR1 (Akademik)
                'Menunggu_WR1' => 'Menunggu_WR2',          // WR1 -> WR2 (Dana)
                'Menunggu_WR2' => 'Disetujui_Final',       // WR2 -> Final
            ],
        ];
        
        $activeFlow = $flows[$jalur] ?? $flows['akademik'];

        return $activeFlow[$currentStatus] ?? $currentStatus;
    }
}