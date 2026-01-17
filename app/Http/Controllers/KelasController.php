<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kelas;

/**
 * KelasController
 * Controller untuk mengelola CRUD data Kelas
 */
class KelasController extends Controller
{
    /**
     * Display a listing of the resource.
     * Menampilkan daftar semua kelas
     */
    public function index()
    {
        // Ambil semua data kelas dengan jumlah siswa
        $kelas = Kelas::withCount('siswas')->get();
        
        return view('kelas.index', compact('kelas'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('kelas.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'kelas' => 'required|unique:kelas|max:10'
        ]);
        
        Kelas::create($request->all());
        
        return redirect()->route('kelas.index')
            ->with('success', 'Data kelas berhasil ditambahkan!');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $kelas = Kelas::findOrFail($id);
        
        return view('kelas.edit', compact('kelas'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'kelas' => 'required|max:10|unique:kelas,kelas,' . $id
        ]);
        
        $kelas = Kelas::findOrFail($id);
        $kelas->update($request->all());
        
        return redirect()->route('kelas.index')
            ->with('success', 'Data kelas berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $kelas = Kelas::findOrFail($id);
        
        if ($kelas->siswas()->count() > 0) {
            return redirect()->route('kelas.index')
                ->with('error', 'Kelas tidak bisa dihapus karena masih digunakan oleh siswa!');
        }
        
        $kelas->delete();
        
        return redirect()->route('kelas.index')
            ->with('success', 'Data kelas berhasil dihapus!');
    }
}
