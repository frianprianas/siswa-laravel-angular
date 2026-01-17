{{--
    File: jurusan/index.blade.php
    Halaman daftar semua jurusan
--}}

@extends('layouts.app')

@section('title', 'Data Jurusan')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card shadow">
            <div class="card-header bg-primary text-white">
                <div class="d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">
                        <i class="fas fa-book"></i> Data Jurusan
                    </h4>
                    <a href="{{ route('jurusan.create') }}" class="btn btn-light btn-sm">
                        <i class="fas fa-plus"></i> Tambah Jurusan
                    </a>
                </div>
            </div>
            <div class="card-body">
                
                @if($jurusans->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover">
                            <thead class="table-dark">
                                <tr>
                                    <th width="5%">No</th>
                                    <th>Nama Jurusan</th>
                                    <th>Bidang Keahlian</th>
                                    <th width="15%">Jumlah Siswa</th>
                                    <th width="15%">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($jurusans as $j)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>
                                            <strong>{{ $j->jurusan }}</strong>
                                        </td>
                                        <td>
                                            <span class="badge bg-info">
                                                {{ $j->bidang }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge bg-success">
                                                {{ $j->siswas_count }} Siswa
                                            </span>
                                        </td>
                                        <td>
                                            <a href="{{ route('jurusan.edit', $j->id) }}" 
                                               class="btn btn-warning btn-sm">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            
                                            <form action="{{ route('jurusan.destroy', $j->id) }}" 
                                                  method="POST" 
                                                  class="d-inline"
                                                  onsubmit="return confirm('Yakin ingin menghapus jurusan {{ $j->jurusan }}?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i> 
                        Belum ada data jurusan. Silakan tambah data baru.
                    </div>
                @endif
                
            </div>
        </div>
    </div>
</div>
@endsection
