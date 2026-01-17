<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Kelas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

/**
 * API Controller untuk Kelas
 * Menangani request dari Angular.js dan mengembalikan response JSON
 */
class KelasApiController extends Controller
{
    /**
     * Display a listing of the resource.
     * GET /api/kelas
     */
    public function index()
    {
        // Ambil semua data kelas dengan jumlah siswa
        $kelas = Kelas::withCount('siswas')->get();
        
        return response()->json([
            'success' => true,
            'message' => 'Data kelas berhasil diambil',
            'data' => $kelas
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     * POST /api/kelas
     */
    public function store(Request $request)
    {
        // Validasi input
        $validator = Validator::make($request->all(), [
            'kelas' => 'required|string|max:10|unique:kelas,kelas',
        ], [
            'kelas.required' => 'Nama kelas wajib diisi',
            'kelas.unique' => 'Nama kelas sudah ada',
            'kelas.max' => 'Nama kelas maksimal 10 karakter',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        // Simpan data kelas
        $kelas = Kelas::create($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Data kelas berhasil ditambahkan',
            'data' => $kelas
        ], 201);
    }

    /**
     * Display the specified resource.
     * GET /api/kelas/{id}
     */
    public function show($id)
    {
        $kelas = Kelas::withCount('siswas')->find($id);

        if (!$kelas) {
            return response()->json([
                'success' => false,
                'message' => 'Data kelas tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Data kelas berhasil diambil',
            'data' => $kelas
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     * PUT /api/kelas/{id}
     */
    public function update(Request $request, $id)
    {
        $kelas = Kelas::find($id);

        if (!$kelas) {
            return response()->json([
                'success' => false,
                'message' => 'Data kelas tidak ditemukan'
            ], 404);
        }

        // Validasi input (unique kecuali untuk data ini sendiri)
        $validator = Validator::make($request->all(), [
            'kelas' => 'required|string|max:10|unique:kelas,kelas,' . $id,
        ], [
            'kelas.required' => 'Nama kelas wajib diisi',
            'kelas.unique' => 'Nama kelas sudah ada',
            'kelas.max' => 'Nama kelas maksimal 10 karakter',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        // Update data kelas
        $kelas->update($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Data kelas berhasil diupdate',
            'data' => $kelas
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     * DELETE /api/kelas/{id}
     */
    public function destroy($id)
    {
        $kelas = Kelas::withCount('siswas')->find($id);

        if (!$kelas) {
            return response()->json([
                'success' => false,
                'message' => 'Data kelas tidak ditemukan'
            ], 404);
        }

        // Cek apakah kelas masih memiliki siswa
        if ($kelas->siswas_count > 0) {
            return response()->json([
                'success' => false,
                'message' => 'Tidak dapat menghapus kelas yang masih memiliki siswa',
                'siswa_count' => $kelas->siswas_count
            ], 400);
        }

        $kelas->delete();

        return response()->json([
            'success' => true,
            'message' => 'Data kelas berhasil dihapus'
        ], 200);
    }
}
