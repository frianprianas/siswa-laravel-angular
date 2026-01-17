{{-- 
    File View: edit.blade.php
    Fungsi: Menampilkan form untuk mengedit data siswa yang sudah ada
    Mirip dengan create.blade.php tapi sudah ada nilai default dari database
--}}

@extends('layouts.app')

@section('title', 'Edit Data Siswa')

@section('content')
    <div class="container-fluid">
        <div class="card shadow">
            <div class="card-header bg-warning text-dark">
                <h3 class="mb-0">
                    <i class="fas fa-user-edit"></i> Edit Data Siswa
                </h3>
            </div>
            <div class="card-body">
                
                {{-- Menampilkan error validasi jika ada --}}
                @if($errors->any())
                    <div class="alert alert-danger">
                        <strong><i class="fas fa-exclamation-triangle"></i> Perhatian!</strong>
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                
                {{-- 
                    Form untuk edit data siswa
                    route('siswa.update', $siswa->id): URL tujuan dengan parameter ID siswa
                    method="POST": HTML form hanya support GET dan POST
                --}}
                <form action="{{ route('siswa.update', $siswa->id) }}" method="POST">
                    
                    {{-- CSRF token untuk security --}}
                    @csrf
                    
                    {{-- 
                        @method('PUT') adalah blade directive untuk method spoofing
                        Karena HTML form tidak support method PUT, kita simulasikan menggunakan @method
                        Laravel akan mendeteksi ini sebagai request PUT
                        Method PUT digunakan untuk update data (RESTful convention)
                    --}}
                    @method('PUT')
                    
                    {{-- Input NIS --}}
                    <div class="mb-3">
                        <label for="nis" class="form-label">
                            NIS (Nomor Induk Siswa) <span class="text-danger">*</span>
                        </label>
                        
                        {{-- 
                            value="{{ old('nis', $siswa->nis) }}"
                            Penjelasan:
                            - old('nis') akan digunakan jika ada error validasi (nilai yang baru diinput)
                            - $siswa->nis adalah nilai default dari database
                            - Jika old('nis') kosong, maka gunakan $siswa->nis
                        --}}
                        <input type="text" 
                               class="form-control @error('nis') is-invalid @enderror" 
                               id="nis" 
                               name="nis" 
                               value="{{ old('nis', $siswa->nis) }}"
                               placeholder="Contoh: 12345"
                               required>
                        @error('nis')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    {{-- Input Nama --}}
                    <div class="mb-3">
                        <label for="nama" class="form-label">
                            Nama Lengkap <span class="text-danger">*</span>
                        </label>
                        {{-- old('nama', $siswa->nama): gunakan old jika ada, jika tidak gunakan data dari database --}}
                        <input type="text" 
                               class="form-control @error('nama') is-invalid @enderror" 
                               id="nama" 
                               name="nama" 
                               value="{{ old('nama', $siswa->nama) }}"
                               placeholder="Contoh: Ahmad Fauzi"
                               required>
                        @error('nama')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    {{-- Input Tempat Lahir --}}
                    <div class="mb-3">
                        <label for="tempat" class="form-label">
                            Tempat Lahir <span class="text-danger">*</span>
                        </label>
                        <input type="text" 
                               class="form-control @error('tempat') is-invalid @enderror" 
                               id="tempat" 
                               name="tempat" 
                               value="{{ old('tempat', $siswa->tempat) }}"
                               placeholder="Contoh: Jakarta"
                               required>
                        @error('tempat')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    {{-- Input Tanggal Lahir --}}
                    <div class="mb-3">
                        <label for="tgl_lahir" class="form-label">
                            Tanggal Lahir <span class="text-danger">*</span>
                        </label>
                        {{-- 
                            $siswa->tgl_lahir->format('Y-m-d')
                            - $siswa->tgl_lahir adalah objek Carbon (tanggal)
                            - format('Y-m-d') mengubah format menjadi YYYY-MM-DD yang sesuai dengan input type="date"
                            - Contoh: 2005-08-17
                        --}}
                        <input type="date" 
                               class="form-control @error('tgl_lahir') is-invalid @enderror" 
                               id="tgl_lahir" 
                               name="tgl_lahir" 
                               value="{{ old('tgl_lahir', $siswa->tgl_lahir->format('Y-m-d')) }}"
                               required>
                        @error('tgl_lahir')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    {{-- Input Jenis Kelamin --}}
                    <div class="mb-3">
                        <label class="form-label">
                            Jenis Kelamin <span class="text-danger">*</span>
                        </label>
                        <div>
                            {{-- Radio button Laki-laki --}}
                            <div class="form-check form-check-inline">
                                {{-- 
                                    checked="{{ old('jenis_kelamin', $siswa->jenis_kelamin) == 'L' ? 'checked' : '' }}"
                                    Penjelasan:
                                    - Cek old('jenis_kelamin') dulu, jika kosong ambil $siswa->jenis_kelamin
                                    - Jika nilainya 'L', tambahkan attribute checked
                                    - checked akan menandai radio button sebagai terpilih
                                --}}
                                <input class="form-check-input @error('jenis_kelamin') is-invalid @enderror" 
                                       type="radio" 
                                       name="jenis_kelamin" 
                                       id="laki" 
                                       value="L"
                                       {{ old('jenis_kelamin', $siswa->jenis_kelamin) == 'L' ? 'checked' : '' }}>
                                <label class="form-check-label" for="laki">
                                    <i class="fas fa-male"></i> Laki-laki
                                </label>
                            </div>
                            
                            {{-- Radio button Perempuan --}}
                            <div class="form-check form-check-inline">
                                <input class="form-check-input @error('jenis_kelamin') is-invalid @enderror" 
                                       type="radio" 
                                       name="jenis_kelamin" 
                                       id="perempuan" 
                                       value="P"
                                       {{ old('jenis_kelamin', $siswa->jenis_kelamin) == 'P' ? 'checked' : '' }}>
                                <label class="form-check-label" for="perempuan">
                                    <i class="fas fa-female"></i> Perempuan
                                </label>
                            </div>
                            
                            @error('jenis_kelamin')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    {{-- Dropdown Kelas --}}
                    <div class="mb-3">
                        <label for="kelas_id" class="form-label">
                            Kelas <span class="text-danger">*</span>
                        </label>
                        {{-- 
                            Dropdown untuk memilih kelas
                            $kelas berisi collection data kelas dari controller
                            $siswa->kelas_id adalah nilai kelas yang sudah dipilih sebelumnya
                        --}}
                        <select class="form-select @error('kelas_id') is-invalid @enderror" 
                                id="kelas_id" 
                                name="kelas_id" 
                                required>
                            <option value="">-- Pilih Kelas --</option>
                            {{-- Loop data kelas dari database --}}
                            @foreach($kelas as $k)
                                {{-- 
                                    old('kelas_id', $siswa->kelas_id) == $k->id ? 'selected' : ''
                                    Menandai option sebagai selected jika:
                                    - Ada old('kelas_id') dan nilainya sama dengan $k->id, ATAU
                                    - Nilai $siswa->kelas_id sama dengan $k->id (data dari database)
                                --}}
                                <option value="{{ $k->id }}" 
                                    {{ old('kelas_id', $siswa->kelas_id) == $k->id ? 'selected' : '' }}>
                                    {{ $k->kelas }}
                                </option>
                            @endforeach
                        </select>
                        @error('kelas_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    {{-- Dropdown Jurusan --}}
                    <div class="mb-3">
                        <label for="jurusan_id" class="form-label">
                            Jurusan <span class="text-danger">*</span>
                        </label>
                        {{-- 
                            Dropdown untuk memilih jurusan
                            $jurusans berisi collection data jurusan dari controller
                            $siswa->jurusan_id adalah nilai jurusan yang sudah dipilih sebelumnya
                        --}}
                        <select class="form-select @error('jurusan_id') is-invalid @enderror" 
                                id="jurusan_id" 
                                name="jurusan_id" 
                                required>
                            <option value="">-- Pilih Jurusan --</option>
                            {{-- Loop data jurusan dari database --}}
                            @foreach($jurusans as $j)
                                {{-- Menampilkan jurusan dan bidang, serta menandai selected jika sesuai --}}
                                <option value="{{ $j->id }}" 
                                    {{ old('jurusan_id', $siswa->jurusan_id) == $j->id ? 'selected' : '' }}>
                                    {{ $j->jurusan }} ({{ $j->bidang }})
                                </option>
                            @endforeach
                        </select>
                        @error('jurusan_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    {{-- Tombol Aksi --}}
                    <div class="d-flex justify-content-between">
                        {{-- Tombol Kembali ke halaman daftar siswa --}}
                        <a href="{{ route('siswa.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Kembali
                        </a>
                        
                        {{-- 
                            Tombol Update
                            Ketika diklik, form akan dikirim ke route siswa.update dengan method PUT
                            Data yang dikirim akan diproses di SiswaController->update()
                        --}}
                        <button type="submit" class="btn btn-warning">
                            <i class="fas fa-save"></i> Update Data
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection