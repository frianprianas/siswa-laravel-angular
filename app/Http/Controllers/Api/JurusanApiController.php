<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Jurusan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

/**
 * API Controller untuk Jurusan
 * Menangani request dari Angular.js dan mengembalikan response JSON
 */
class JurusanApiController extends Controller
{
    /**
     * Display a listing of the resource.
     * GET /api/jurusan
     */
    public function index()
    {
        // Ambil semua data jurusan dengan jumlah siswa
        $jurusans = Jurusan::withCount('siswas')->get();
        
        return response()->json([
            'success' => true,
            'message' => 'Data jurusan berhasil diambil',
            'data' => $jurusans
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     * POST /api/jurusan
     */
    public function store(Request $request)
    {
        // Validasi input
        $validator = Validator::make($request->all(), [
            'jurusan' => 'required|string|max:50|unique:jurusans,jurusan',
            'bidang' => 'required|string|max:100',
        ], [
            'jurusan.required' => 'Nama jurusan wajib diisi',
            'jurusan.unique' => 'Nama jurusan sudah ada',
            'jurusan.max' => 'Nama jurusan maksimal 50 karakter',
            'bidang.required' => 'Bidang keahlian wajib diisi',
            'bidang.max' => 'Bidang keahlian maksimal 100 karakter',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        // Simpan data jurusan
        $jurusan = Jurusan::create($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Data jurusan berhasil ditambahkan',
            'data' => $jurusan
        ], 201);
    }

    /**
     * Display the specified resource.
     * GET /api/jurusan/{id}
     */
    public function show($id)
    {
        $jurusan = Jurusan::withCount('siswas')->find($id);

        if (!$jurusan) {
            return response()->json([
                'success' => false,
                'message' => 'Data jurusan tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Data jurusan berhasil diambil',
            'data' => $jurusan
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     * PUT /api/jurusan/{id}
     */
    public function update(Request $request, $id)
    {
        $jurusan = Jurusan::find($id);

        if (!$jurusan) {
            return response()->json([
                'success' => false,
                'message' => 'Data jurusan tidak ditemukan'
            ], 404);
        }

        // Validasi input (unique kecuali untuk data ini sendiri)
        $validator = Validator::make($request->all(), [
            'jurusan' => 'required|string|max:50|unique:jurusans,jurusan,' . $id,
            'bidang' => 'required|string|max:100',
        ], [
            'jurusan.required' => 'Nama jurusan wajib diisi',
            'jurusan.unique' => 'Nama jurusan sudah ada',
            'jurusan.max' => 'Nama jurusan maksimal 50 karakter',
            'bidang.required' => 'Bidang keahlian wajib diisi',
            'bidang.max' => 'Bidang keahlian maksimal 100 karakter',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        // Update data jurusan
        $jurusan->update($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Data jurusan berhasil diupdate',
            'data' => $jurusan
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     * DELETE /api/jurusan/{id}
     */
    public function destroy($id)
    {
        $jurusan = Jurusan::withCount('siswas')->find($id);

        if (!$jurusan) {
            return response()->json([
                'success' => false,
                'message' => 'Data jurusan tidak ditemukan'
            ], 404);
        }

        // Cek apakah jurusan masih memiliki siswa
        if ($jurusan->siswas_count > 0) {
            return response()->json([
                'success' => false,
                'message' => 'Tidak dapat menghapus jurusan yang masih memiliki siswa',
                'siswa_count' => $jurusan->siswas_count
            ], 400);
        }

        $jurusan->delete();

        return response()->json([
            'success' => true,
            'message' => 'Data jurusan berhasil dihapus'
        ], 200);
    }
    
    /**
     * Export data jurusan ke Excel/CSV
     * GET /api/jurusan/export
     */
    public function export()
    {
        $jurusans = \App\Models\Jurusan::withCount('siswas')->get();
        
        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="data_jurusan_' . date('Y-m-d_His') . '.csv"',
        ];
        
        $callback = function() use ($jurusans) {
            $file = fopen('php://output', 'w');
            
            // BOM untuk UTF-8
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            
            // Header kolom
            fputcsv($file, [
                'Jurusan',
                'Keterangan',
                'Jumlah Siswa'
            ], ';');
            
            // Data
            foreach ($jurusans as $j) {
                fputcsv($file, [
                    $j->jurusan,
                    $j->keterangan ?: '-',
                    $j->siswas_count
                ], ';');
            }
            
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }
}
