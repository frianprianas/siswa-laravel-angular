{{--
    File: kelas/edit.blade.php
    Form untuk edit data kelas
--}}

@extends('layouts.app')

@section('title', 'Edit Kelas')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card shadow">
            <div class="card-header bg-warning">
                <h4 class="mb-0">
                    <i class="fas fa-edit"></i> Edit Data Kelas
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
                
                {{-- Form edit kelas --}}
                <form action="{{ route('kelas.update', $kelas->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-3">
                        <label for="kelas" class="form-label">
                            Nama Kelas <span class="text-danger">*</span>
                        </label>
                        <input type="text" 
                               class="form-control @error('kelas') is-invalid @enderror" 
                               id="kelas" 
                               name="kelas" 
                               value="{{ old('kelas', $kelas->kelas) }}"
                               placeholder="Contoh: XA, XB, XIA, XIIC"
                               maxlength="10"
                               required>
                        @error('kelas')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('kelas.index') }}" class="btn btn-secondary">
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
