{{-- 
    File View: index.blade.php
    Fungsi: Menampilkan daftar semua siswa dalam bentuk tabel
    
    Blade adalah template engine Laravel yang memudahkan kita menulis HTML dengan PHP
    Sintaks {{ }} digunakan untuk menampilkan data/variabel PHP
    Sintaks @directive adalah blade directive untuk struktur kontrol (if, foreach, dll)
--}}

@extends('layouts.app')

@section('title', 'Data Siswa')

@section('content')
    {{-- Container Bootstrap untuk layout yang responsive --}}
    <div class="container-fluid">
        {{-- Card Bootstrap untuk membuat box dengan border dan shadow --}}
        <div class="card shadow">
            <div class="card-header bg-primary text-white">
                <h3 class="mb-0">
                    <i class="fas fa-users"></i> Data Siswa
                </h3>
            </div>
            <div class="card-body">
                
                {{-- Tombol untuk menambah data siswa baru --}}
                {{-- route('siswa.create') menghasilkan URL ke halaman form tambah siswa --}}
                <div class="mb-3">
                    <a href="{{ route('siswa.create') }}" class="btn btn-success">
                        <i class="fas fa-plus"></i> Tambah Siswa Baru
                    </a>
                </div>
                
                {{-- Tabel untuk menampilkan data siswa --}}
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover">
                        {{-- Thead adalah bagian header tabel --}}
                        <thead class="table-dark">
                            <tr>
                                <th width="5%">No</th>
                                <th>NIS</th>
                                <th>Nama</th>
                                <th>Tempat Lahir</th>
                                <th>Tanggal Lahir</th>
                                <th>Jenis Kelamin</th>
                                <th>Kelas</th>
                                <th>Jurusan</th>
                                <th width="15%">Aksi</th>
                            </tr>
                        </thead>
                        {{-- Tbody adalah bagian isi tabel --}}
                        <tbody>
                            {{-- 
                                @forelse adalah blade directive untuk looping
                                Lebih baik dari @foreach karena ada fallback @empty jika data kosong
                                $siswas adalah variabel yang dikirim dari controller
                                $loop adalah variabel otomatis Laravel dalam foreach untuk tracking iterasi
                            --}}
                            @forelse($siswas as $siswa)
                                <tr>
                                    {{-- $loop->iteration menampilkan nomor urut otomatis mulai dari 1 --}}
                                    <td>{{ $loop->iteration }}</td>
                                    
                                    {{-- {{ $siswa->nis }} menampilkan data NIS dari object siswa --}}
                                    <td>{{ $siswa->nis }}</td>
                                    <td>{{ $siswa->nama }}</td>
                                    <td>{{ $siswa->tempat }}</td>
                                    
                                    {{-- 
                                        format('d-m-Y') mengubah format tanggal dari YYYY-MM-DD menjadi DD-MM-YYYY
                                        Contoh: 2005-08-17 menjadi 17-08-2005
                                    --}}
                                    <td>{{ $siswa->tgl_lahir->format('d-m-Y') }}</td>
                                    
                                    {{-- 
                                        Operator ternary (kondisi ? nilai_jika_benar : nilai_jika_salah)
                                        Menampilkan "Laki-laki" jika L, atau "Perempuan" jika P
                                    --}}
                                    <td>{{ $siswa->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}</td>
                                    
                                    {{-- 
                                        Menampilkan kelas menggunakan relasi
                                        $siswa->kelas->kelas mengakses nama kelas melalui relasi belongsTo
                                        
                                        Analogi Native PHP:
                                        SELECT kelas FROM kelas WHERE id = $siswa->kelas_id
                                        
                                        ?? '-' adalah null coalescing operator
                                        Jika $siswa->kelas null, tampilkan '-'
                                    --}}
                                    <td>
                                        <span class="badge bg-info">
                                            {{ $siswa->kelas->kelas ?? '-' }}
                                        </span>
                                    </td>
                                    
                                    {{-- 
                                        Menampilkan jurusan menggunakan relasi
                                        $siswa->jurusan->jurusan mengakses nama jurusan melalui relasi belongsTo
                                        
                                        Analogi Native PHP:
                                        SELECT jurusan, bidang FROM jurusans WHERE id = $siswa->jurusan_id
                                    --}}
                                    <td>
                                        <span class="badge bg-success">
                                            {{ $siswa->jurusan->jurusan ?? '-' }}
                                        </span>
                                        <br>
                                        <small class="text-muted">
                                            {{ $siswa->jurusan->bidang ?? '-' }}
                                        </small>
                                    </td>
                                    
                                    <td>
                                        {{-- Tombol Edit --}}
                                        {{-- route('siswa.edit', $siswa->id) menghasilkan URL /siswa/{id}/edit --}}
                                        <a href="{{ route('siswa.edit', $siswa->id) }}" 
                                           class="btn btn-warning btn-sm">
                                            <i class="fas fa-edit"></i> Edit
                                        </a>
                                        
                                        {{-- 
                                            Form untuk Delete
                                            Method DELETE tidak didukung HTML form, jadi menggunakan @method('DELETE')
                                            Ini adalah blade directive untuk method spoofing
                                        --}}
                                        <form action="{{ route('siswa.destroy', $siswa->id) }}" 
                                              method="POST" 
                                              class="d-inline"
                                              onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?')">
                                            
                                            {{-- 
                                                @csrf adalah blade directive untuk CSRF token
                                                Token ini wajib untuk setiap form POST/PUT/DELETE sebagai security
                                                Melindungi dari CSRF (Cross-Site Request Forgery) attack
                                            --}}
                                            @csrf
                                            
                                            {{-- @method('DELETE') memberitahu Laravel bahwa ini adalah request DELETE --}}
                                            @method('DELETE')
                                            
                                            <button type="submit" class="btn btn-danger btn-sm">
                                                <i class="fas fa-trash"></i> Hapus
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            
                            {{-- 
                                @empty akan dieksekusi jika $siswas kosong (tidak ada data)
                                colspan="9" membuat cell mengambil 9 kolom (merge cell)
                            --}}
                            @empty
                                <tr>
                                    <td colspan="9" class="text-center text-muted">
                                        <i class="fas fa-info-circle"></i> 
                                        Belum ada data siswa. Silakan tambah data baru.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection