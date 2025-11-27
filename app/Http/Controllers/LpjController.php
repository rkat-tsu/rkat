<?php

namespace App\Http\Controllers;

use App\Models\Lpj;
use App\Models\RkatHeader;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class LpjController extends Controller
{
    /**
     * Tampilkan daftar LPJ
     */
    public function index(Request $request)
    {
        $search = $request->input('search');

        $lpj = Lpj::with('rkat.unit')
            ->when($search, fn($q) => $q->search($search))
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return Inertia::render('Lpj/Index', [
            'lpj' => $lpj,
            'filters' => ['search' => $search]
        ]);
    }

    /**
     * Form tambah LPJ
     */
    public function create()
    {
        // Hanya RKAT yang sudah disetujui boleh dibuat LPJ
        $rkat = RkatHeader::where('status', 'APPROVED')->with('unit')->get();

        return Inertia::render('Lpj/Create', [
            'rkat' => $rkat
        ]);
    }

    /**
     * Simpan LPJ baru
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'rkat_id'           => 'required|exists:rkat_headers,id',
            'judul'            => 'required|string|max:150',
            'tempat'           => 'required|string|max:150',
            'tanggal_mulai'     => 'required|date',
            'tanggal_selesai'   => 'required|date|after_or_equal:tanggal_mulai',
            'anggaran'          => 'required|numeric|min:0',
            'keterangan'        => 'nullable|string',
            'file_lpj'          => 'nullable|file|mimes:pdf|max:4096', // max 4MB
        ]);

        if ($request->hasFile('file_lpj')) {
            $validated['file_lpj'] = $request->file('file_lpj')->store('lpj_files', 'public');
        }

        $validated['dibuat_oleh'] = Auth::id();

        Lpj::create($validated);

        return redirect()->route('lpj.index')->with('success', 'LPJ berhasil ditambahkan');
    }

    /**
     * Edit LPJ
     */
    public function edit(Lpj $lpj)
    {
        $rkat = RkatHeader::where('status', 'APPROVED')->get();

        return Inertia::render('Lpj/Edit', [
            'lpj'  => $lpj,
            'rkat' => $rkat
        ]);
    }

    /**
     * Update LPJ
     */
    public function update(Request $request, Lpj $lpj)
    {
        $validated = $request->validate([
            'rkat_id'           => 'required|exists:rkat_headers,id',
            'judul'            => 'required|string|max:150',
            'tempat'           => 'required|string|max:150',
            'tanggal_mulai'     => 'required|date',
            'tanggal_selesai'   => 'required|date|after_or_equal:tanggal_mulai',
            'anggaran'          => 'required|numeric|min:0',
            'keterangan'        => 'nullable|string',
            'file_lpj'          => 'nullable|file|mimes:pdf|max:4096',
        ]);

        if ($request->hasFile('file_lpj')) {

            // Hapus file lama jika ada
            if ($lpj->file_lpj && Storage::disk('public')->exists($lpj->file_lpj)) {
                Storage::disk('public')->delete($lpj->file_lpj);
            }

            $validated['file_lpj'] = $request->file('file_lpj')->store('lpj_files', 'public');
        }

        $lpj->update($validated);

        return redirect()->route('lpj.index')->with('success', 'LPJ berhasil diperbarui');
    }

    /**
     * Hapus LPJ (Soft Delete)
     */
    public function destroy(Lpj $lpj)
    {
        $lpj->delete();
        return redirect()->back()->with('success', 'LPJ berhasil dihapus');
    }
}
