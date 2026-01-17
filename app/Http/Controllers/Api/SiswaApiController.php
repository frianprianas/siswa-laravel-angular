<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Siswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

/**
 * API Controller untuk Siswa
 * Menangani request dari Angular.js dan mengembalikan response JSON
 */
class SiswaApiController extends Controller
{
    /**
     * Display a listing of the resource.
     * GET /api/siswa
     */
    public function index()
    {
        // Ambil semua data siswa dengan relasi kelas dan jurusan
        $siswas = Siswa::with(['kelas', 'jurusan'])->get();
        
        return response()->json([
            'success' => true,
            'message' => 'Data siswa berhasil diambil',
            'data' => $siswas
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     * POST /api/siswa
     */
    public function store(Request $request)
    {
        // Validasi input
        $validator = Validator::make($request->all(), [
            'nis' => 'required|string|max:20|unique:siswas,nis',
            'nama' => 'required|string|max:100',
            'tempat' => 'required|string|max:100',
            'tgl_lahir' => 'required|date',
            'jenis_kelamin' => 'required|in:L,P',
            'kelas_id' => 'required|exists:kelas,id',
            'jurusan_id' => 'required|exists:jurusans,id',
        ], [
            'nis.required' => 'NIS wajib diisi',
            'nis.unique' => 'NIS sudah terdaftar',
            'nama.required' => 'Nama wajib diisi',
            'tempat.required' => 'Tempat lahir wajib diisi',
            'tgl_lahir.required' => 'Tanggal lahir wajib diisi',
            'tgl_lahir.date' => 'Format tanggal lahir tidak valid',
            'jenis_kelamin.required' => 'Jenis kelamin wajib dipilih',
            'jenis_kelamin.in' => 'Jenis kelamin harus L atau P',
            'kelas_id.required' => 'Kelas wajib dipilih',
            'kelas_id.exists' => 'Kelas tidak ditemukan',
            'jurusan_id.required' => 'Jurusan wajib dipilih',
            'jurusan_id.exists' => 'Jurusan tidak ditemukan',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        // Simpan data siswa
        $siswa = Siswa::create($request->all());
        
        // Load relasi untuk response
        $siswa->load(['kelas', 'jurusan']);

        return response()->json([
            'success' => true,
            'message' => 'Data siswa berhasil ditambahkan',
            'data' => $siswa
        ], 201);
    }

    /**
     * Display the specified resource.
     * GET /api/siswa/{id}
     */
    public function show($id)
    {
        $siswa = Siswa::with(['kelas', 'jurusan'])->find($id);

        if (!$siswa) {
            return response()->json([
                'success' => false,
                'message' => 'Data siswa tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Data siswa berhasil diambil',
            'data' => $siswa
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     * PUT /api/siswa/{id}
     */
    public function update(Request $request, $id)
    {
        $siswa = Siswa::find($id);

        if (!$siswa) {
            return response()->json([
                'success' => false,
                'message' => 'Data siswa tidak ditemukan'
            ], 404);
        }

        // Validasi input (NIS unique kecuali untuk data ini sendiri)
        $validator = Validator::make($request->all(), [
            'nis' => 'required|string|max:20|unique:siswas,nis,' . $id,
            'nama' => 'required|string|max:100',
            'tempat' => 'required|string|max:100',
            'tgl_lahir' => 'required|date',
            'jenis_kelamin' => 'required|in:L,P',
            'kelas_id' => 'required|exists:kelas,id',
            'jurusan_id' => 'required|exists:jurusans,id',
        ], [
            'nis.required' => 'NIS wajib diisi',
            'nis.unique' => 'NIS sudah terdaftar',
            'nama.required' => 'Nama wajib diisi',
            'tempat.required' => 'Tempat lahir wajib diisi',
            'tgl_lahir.required' => 'Tanggal lahir wajib diisi',
            'tgl_lahir.date' => 'Format tanggal lahir tidak valid',
            'jenis_kelamin.required' => 'Jenis kelamin wajib dipilih',
            'jenis_kelamin.in' => 'Jenis kelamin harus L atau P',
            'kelas_id.required' => 'Kelas wajib dipilih',
            'kelas_id.exists' => 'Kelas tidak ditemukan',
            'jurusan_id.required' => 'Jurusan wajib dipilih',
            'jurusan_id.exists' => 'Jurusan tidak ditemukan',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        // Update data siswa
        $siswa->update($request->all());
        
        // Load relasi untuk response
        $siswa->load(['kelas', 'jurusan']);

        return response()->json([
            'success' => true,
            'message' => 'Data siswa berhasil diupdate',
            'data' => $siswa
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     * DELETE /api/siswa/{id}
     */
    public function destroy($id)
    {
        $siswa = Siswa::find($id);

        if (!$siswa) {
            return response()->json([
                'success' => false,
                'message' => 'Data siswa tidak ditemukan'
            ], 404);
        }

        $siswa->delete();

        return response()->json([
            'success' => true,
            'message' => 'Data siswa berhasil dihapus'
        ], 200);
    }
}
