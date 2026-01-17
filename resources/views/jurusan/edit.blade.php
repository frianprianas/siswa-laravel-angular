{{--
    File: jurusan/edit.blade.php
    Form untuk edit data jurusan
--}}

@extends('layouts.app')

@section('title', 'Edit Jurusan')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow">
            <div class="card-header bg-warning">
                <h4 class="mb-0">
                    <i class="fas fa-edit"></i> Edit Data Jurusan
                </h4>
            </div>
            <div class="card-body">
                
                {{-- Menampilkan error validasi --}}
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
                
                {{-- Form edit jurusan --}}
                <form action="{{ route('jurusan.update', $jurusan->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="jurusan" class="form-label">
                                    Nama Jurusan <span class="text-danger">*</span>
                                </label>
                                <input type="text" 
                                       class="form-control @error('jurusan') is-invalid @enderror" 
                                       id="jurusan" 
                                       name="jurusan" 
                                       value="{{ old('jurusan', $jurusan->jurusan) }}"
                                       placeholder="Contoh: TKJ, RPL, AKT"
                                       maxlength="50"
                                       required>
                                @error('jurusan')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="bidang" class="form-label">
                                    Bidang Keahlian <span class="text-danger">*</span>
                                </label>
                                <input type="text" 
                                       class="form-control @error('bidang') is-invalid @enderror" 
                                       id="bidang" 
                                       name="bidang" 
                                       value="{{ old('bidang', $jurusan->bidang) }}"
                                       placeholder="Contoh: Informatika"
                                       maxlength="100"
                                       required>
                                @error('bidang')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('jurusan.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Kembali
                        </a>
                        <button type="submit" class="btn btn-warning">
                            <i class="fas fa-save"></i> Update
                        </button>
                    </div>
                </form>
                
            </div>
        </div>
    </div>
</div>
@endsection
