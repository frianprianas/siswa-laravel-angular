<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Jurusan;

/**
 * JurusanController
 * Controller untuk mengelola CRUD data Jurusan
 */
class JurusanController extends Controller
{
    /**
     * Display a listing of the resource.
     * Menampilkan daftar semua jurusan
     */
    public function index()
    {
        // Ambil semua data jurusan dengan jumlah siswa
        $jurusans = Jurusan::withCount('siswas')->get();
        
        return view('jurusan.index', compact('jurusans'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('jurusan.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'jurusan' => 'required|max:50',
            'bidang' => 'required|max:100'
        ]);
        
        Jurusan::create($request->all());
        
        return redirect()->route('jurusan.index')
            ->with('success', 'Data jurusan berhasil ditambahkan!');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $jurusan = Jurusan::findOrFail($id);
        
        return view('jurusan.edit', compact('jurusan'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'jurusan' => 'required|max:50',
            'bidang' => 'required|max:100'
        ]);
        
        $jurusan = Jurusan::findOrFail($id);
        $jurusan->update($request->all());
        
        return redirect()->route('jurusan.index')
            ->with('success', 'Data jurusan berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $jurusan = Jurusan::findOrFail($id);
        
        if ($jurusan->siswas()->count() > 0) {
            return redirect()->route('jurusan.index')
                ->with('error', 'Jurusan tidak bisa dihapus karena masih digunakan oleh siswa!');
        }
        
        $jurusan->delete();
        
        return redirect()->route('jurusan.index')
            ->with('success', 'Data jurusan berhasil dihapus!');
    }
}
