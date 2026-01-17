{{-- 
    File View: create.blade.php
    Fungsi: Menampilkan form untuk menambah data siswa baru
    Semua field harus diisi, termasuk kelas dan jurusan
--}}

@extends('layouts.app')

@section('title', 'Tambah Data Siswa')

@section('content')
    <div class="container-fluid">
        <div class="card shadow">
            <div class="card-header bg-success text-white">
                <h3 class="mb-0">
                    <i class="fas fa-user-plus"></i> Tambah Data Siswa Baru
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
                
                {{-- Form untuk tambah data siswa baru --}}
                <form action="{{ route('siswa.store') }}" method="POST">
                    @csrf
                    
                    {{-- Input NIS --}}
                    <div class="mb-3">
                        <label for="nis" class="form-label">
                            NIS (Nomor Induk Siswa) <span class="text-danger">*</span>
                        </label>
                        <input type="text" 
                               class="form-control @error('nis') is-invalid @enderror" 
                               id="nis" 
                               name="nis" 
                               value="{{ old('nis') }}"
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
                        <input type="text" 
                               class="form-control @error('nama') is-invalid @enderror" 
                               id="nama" 
                               name="nama" 
                               value="{{ old('nama') }}"
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
                               value="{{ old('tempat') }}"
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
                        <input type="date" 
                               class="form-control @error('tgl_lahir') is-invalid @enderror" 
                               id="tgl_lahir" 
                               name="tgl_lahir" 
                               value="{{ old('tgl_lahir') }}"
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
                            <div class="form-check form-check-inline">
                                <input class="form-check-input @error('jenis_kelamin') is-invalid @enderror" 
                                       type="radio" 
                                       name="jenis_kelamin" 
                                       id="laki" 
                                       value="L"
                                       {{ old('jenis_kelamin') == 'L' ? 'checked' : '' }}>
                                <label class="form-check-label" for="laki">
                                    <i class="fas fa-male"></i> Laki-laki
                                </label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input @error('jenis_kelamin') is-invalid @enderror" 
                                       type="radio" 
                                       name="jenis_kelamin" 
                                       id="perempuan" 
                                       value="P"
                                       {{ old('jenis_kelamin') == 'P' ? 'checked' : '' }}>
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
                        --}}
                        <select class="form-select @error('kelas_id') is-invalid @enderror" 
                                id="kelas_id" 
                                name="kelas_id" 
                                required>
                            <option value="">-- Pilih Kelas --</option>
                            {{-- Loop data kelas dari database --}}
                            @foreach($kelas as $k)
                                <option value="{{ $k->id }}" {{ old('kelas_id') == $k->id ? 'selected' : '' }}>
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
                        --}}
                        <select class="form-select @error('jurusan_id') is-invalid @enderror" 
                                id="jurusan_id" 
                                name="jurusan_id" 
                                required>
                            <option value="">-- Pilih Jurusan --</option>
                            {{-- Loop data jurusan dari database --}}
                            @foreach($jurusans as $j)
                                <option value="{{ $j->id }}" {{ old('jurusan_id') == $j->id ? 'selected' : '' }}>
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
                        <a href="{{ route('siswa.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Kembali
                        </a>
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-save"></i> Simpan Data
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection