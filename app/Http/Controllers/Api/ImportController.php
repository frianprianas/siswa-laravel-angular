<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Siswa;
use App\Models\Kelas;
use App\Models\Jurusan;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

/**
 * ImportController
 * 
 * Controller untuk handle import data siswa dari file CSV
 * Support delimiter koma (,) atau titik koma (;)
 * 
 * @author Your Name
 * @version 1.0
 */
class ImportController extends Controller
{
    /**
     * Download template CSV untuk import siswa
     * 
     * Template berisi header kolom yang diperlukan dengan contoh data
     * 
     * @return \Illuminate\Http\Response CSV file
     */
    public function downloadTemplate()
    {
        // Ambil data kelas dan jurusan untuk contoh
        $kelas = Kelas::first();
        $jurusan = Jurusan::first();
        
        $kelasNama = $kelas ? $kelas->nama_kelas : 'XII RPL 1';
        $jurusanNama = $jurusan ? $jurusan->nama_jurusan : 'Rekayasa Perangkat Lunak';
        
        // Header CSV
        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="template_import_siswa.csv"',
        ];
        
        // Data template dengan contoh
        $callback = function() use ($kelasNama, $jurusanNama) {
            $file = fopen('php://output', 'w');
            
            // BOM untuk UTF-8 agar Excel bisa baca dengan benar
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            
            // Header kolom
            fputcsv($file, [
                'NIS',
                'Nama',
                'Jenis Kelamin (L/P)',
                'Kelas',
                'Jurusan',
                'Alamat',
                'Tanggal Lahir (YYYY-MM-DD)',
                'Tempat Lahir'
            ], ';');
            
            // Contoh data 1
            fputcsv($file, [
                '123456',
                'John Doe',
                'L',
                $kelasNama,
                $jurusanNama,
                'Jl. Contoh No. 123',
                '2005-01-15',
                'Jakarta'
            ], ';');
            
            // Contoh data 2
            fputcsv($file, [
                '123457',
                'Jane Smith',
                'P',
                $kelasNama,
                $jurusanNama,
                'Jl. Sample No. 456',
                '2005-02-20',
                'Bandung'
            ], ';');
            
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }
    
    /**
     * Import data siswa dari file CSV
     * 
     * Validasi yang dilakukan:
     * - NIS harus unique
     * - Kelas harus exist di database
     * - Jurusan harus exist di database
     * - Jenis kelamin harus L atau P
     * - Tanggal lahir harus valid
     * 
     * @param Request $request File CSV dengan key 'file'
     * @return \Illuminate\Http\JsonResponse
     */
    public function importCSV(Request $request)
    {
        // Validasi request file
        $validator = Validator::make($request->all(), [
            'file' => 'required|file|mimes:csv,txt|max:2048'
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'File CSV tidak valid',
                'errors' => $validator->errors()
            ], 422);
        }
        
        $file = $request->file('file');
        
        // Baca file CSV
        $handle = fopen($file->getRealPath(), 'r');
        
        // Skip BOM jika ada
        $bom = fread($handle, 3);
        if ($bom != chr(0xEF).chr(0xBB).chr(0xBF)) {
            rewind($handle);
        }
        
        $successCount = 0;
        $failedCount = 0;
        $failedRecords = [];
        
        // Baca header
        $header = fgetcsv($handle, 1000, ';');
        if (!$header) {
            $header = fgetcsv($handle, 1000, ',');
            if (!$header) {
                fclose($handle);
                return response()->json([
                    'success' => false,
                    'message' => 'Format CSV tidak valid'
                ], 422);
            }
            rewind($handle);
            // Skip BOM lagi
            $bom = fread($handle, 3);
            if ($bom != chr(0xEF).chr(0xBB).chr(0xBF)) {
                rewind($handle);
            }
            $header = fgetcsv($handle, 1000, ',');
            $delimiter = ',';
        } else {
            $delimiter = ';';
        }
        
        $rowNumber = 1; // Mulai dari 1 (setelah header)
        
        // Baca setiap baris
        while (($row = fgetcsv($handle, 1000, $delimiter)) !== false) {
            $rowNumber++;
            
            // Skip baris kosong
            if (empty(array_filter($row))) {
                continue;
            }
            
            // Ambil data dari CSV
            $nis = isset($row[0]) ? trim($row[0]) : '';
            $nama = isset($row[1]) ? trim($row[1]) : '';
            $jenis_kelamin = isset($row[2]) ? strtoupper(trim($row[2])) : '';
            $kelas_nama = isset($row[3]) ? trim($row[3]) : '';
            $jurusan_nama = isset($row[4]) ? trim($row[4]) : '';
            $alamat = isset($row[5]) ? trim($row[5]) : '';
            $tanggal_lahir = isset($row[6]) ? trim($row[6]) : '';
            $tempat_lahir = isset($row[7]) ? trim($row[7]) : '';
            
            $errors = [];
            
            // Validasi NIS
            if (empty($nis)) {
                $errors[] = 'NIS tidak boleh kosong';
            } elseif (Siswa::where('nis', $nis)->exists()) {
                $errors[] = 'NIS sudah terdaftar';
            }
            
            // Validasi nama
            if (empty($nama)) {
                $errors[] = 'Nama tidak boleh kosong';
            }
            
            // Validasi jenis kelamin
            if (!in_array($jenis_kelamin, ['L', 'P'])) {
                $errors[] = 'Jenis kelamin harus L atau P';
            }
            
            // Cari kelas
            $kelas = Kelas::where('kelas', $kelas_nama)->first();
            if (!$kelas) {
                $errors[] = 'Kelas "' . $kelas_nama . '" tidak ditemukan';
            }
            
            // Cari jurusan
            $jurusan = Jurusan::where('jurusan', $jurusan_nama)->first();
            if (!$jurusan) {
                $errors[] = 'Jurusan "' . $jurusan_nama . '" tidak ditemukan';
            }
            
            // Validasi tanggal lahir (support berbagai format dari Excel)
            if (!empty($tanggal_lahir)) {
                $parsedDate = null;
                
                // Coba berbagai format tanggal
                $formats = [
                    'Y-m-d',           // 2005-01-15
                    'd/m/Y',           // 15/01/2005 (Excel Indonesia)
                    'm/d/Y',           // 01/15/2005 (Excel US)
                    'Y/m/d',           // 2005/01/15
                    'd-m-Y',           // 15-01-2005
                    'm-d-Y',           // 01-15-2005
                ];
                
                // Cek apakah berupa Excel serial number (angka)
                if (is_numeric($tanggal_lahir)) {
                    try {
                        // Excel date serial number (1 = 1 Jan 1900)
                        $excelEpoch = new \DateTime('1899-12-30');
                        $excelEpoch->modify('+' . intval($tanggal_lahir) . ' days');
                        $parsedDate = $excelEpoch;
                    } catch (\Exception $e) {
                        // Skip, akan coba format lain
                    }
                }
                
                // Coba parse dengan berbagai format
                if (!$parsedDate) {
                    foreach ($formats as $format) {
                        $date = \DateTime::createFromFormat($format, $tanggal_lahir);
                        if ($date && $date->format($format) === $tanggal_lahir) {
                            $parsedDate = $date;
                            break;
                        }
                    }
                }
                
                if (!$parsedDate) {
                    $errors[] = 'Format tanggal lahir tidak valid. Gunakan: YYYY-MM-DD, DD/MM/YYYY, atau MM/DD/YYYY';
                } else {
                    // Konversi ke format database
                    $tanggal_lahir = $parsedDate->format('Y-m-d');
                }
            }
            
            // Jika ada error, simpan ke failed records
            if (!empty($errors)) {
                $failedCount++;
                $failedRecords[] = [
                    'row' => $rowNumber,
                    'nis' => $nis,
                    'nama' => $nama,
                    'kelas' => $kelas_nama,
                    'jurusan' => $jurusan_nama,
                    'errors' => implode(', ', $errors)
                ];
                continue;
            }
            
            // Simpan data siswa
            try {
                Siswa::create([
                    'nis' => $nis,
                    'nama' => $nama,
                    'jenis_kelamin' => $jenis_kelamin,
                    'kelas_id' => $kelas->id,
                    'jurusan_id' => $jurusan->id,
                    'alamat' => $alamat ?: null,
                    'tgl_lahir' => !empty($tanggal_lahir) ? $tanggal_lahir : null,
                    'tempat' => $tempat_lahir ?: null
                ]);
                
                $successCount++;
            } catch (\Exception $e) {
                $failedCount++;
                $failedRecords[] = [
                    'row' => $rowNumber,
                    'nis' => $nis,
                    'nama' => $nama,
                    'kelas' => $kelas_nama,
                    'jurusan' => $jurusan_nama,
                    'errors' => 'Error database: ' . $e->getMessage()
                ];
            }
        }
        
        fclose($handle);
        
        return response()->json([
            'success' => true,
            'message' => "Import selesai. Berhasil: $successCount, Gagal: $failedCount",
            'data' => [
                'success_count' => $successCount,
                'failed_count' => $failedCount,
                'failed_records' => $failedRecords
            ]
        ]);
    }
    
    /**
     * Download file CSV untuk records yang gagal
     * 
     * @param Request $request Array failed records
     * @return \Illuminate\Http\Response CSV file
     */
    public function downloadFailedRecords(Request $request)
    {
        $failedRecords = $request->input('failed_records', []);
        
        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="import_gagal_' . date('YmdHis') . '.csv"',
        ];
        
        $callback = function() use ($failedRecords) {
            $file = fopen('php://output', 'w');
            
            // BOM untuk UTF-8
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            
            // Header
            fputcsv($file, ['Baris', 'NIS', 'Nama', 'Kelas', 'Jurusan', 'Keterangan Error'], ';');
            
            // Data
            foreach ($failedRecords as $record) {
                fputcsv($file, [
                    $record['row'],
                    $record['nis'],
                    $record['nama'],
                    $record['kelas'],
                    $record['jurusan'],
                    $record['errors']
                ], ';');
            }
            
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }
}
