{{--
    File: kelas/index.blade.php
    Halaman daftar semua kelas
--}}

@extends('layouts.app')

@section('title', 'Data Kelas')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card shadow">
            <div class="card-header bg-primary text-white">
                <div class="d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">
                        <i class="fas fa-door-open"></i> Data Kelas
                    </h4>
                    <a href="{{ route('kelas.create') }}" class="btn btn-light btn-sm">
                        <i class="fas fa-plus"></i> Tambah Kelas
                    </a>
                </div>
            </div>
            <div class="card-body">
                
                @if($kelas->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover">
                            <thead class="table-dark">
                                <tr>
                                    <th width="5%">No</th>
                                    <th>Nama Kelas</th>
                                    <th width="15%">Jumlah Siswa</th>
                                    <th width="15%">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                {{-- Loop semua data kelas --}}
                                @foreach($kelas as $k)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>
                                            <strong>{{ $k->kelas }}</strong>
                                        </td>
                                        <td>
                                            {{-- 
                                                $k->siswas_count adalah hasil dari withCount('siswas')
                                                Menampilkan jumlah siswa di kelas ini
                                            --}}
                                            <span class="badge bg-info">
                                                {{ $k->siswas_count }} Siswa
                                            </span>
                                        </td>
                                        <td>
                                            {{-- Tombol Edit --}}
                                            <a href="{{ route('kelas.edit', $k->id) }}" 
                                               class="btn btn-warning btn-sm">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            
                                            {{-- Tombol Hapus --}}
                                            <form action="{{ route('kelas.destroy', $k->id) }}" 
                                                  method="POST" 
                                                  class="d-inline"
                                                  onsubmit="return confirm('Yakin ingin menghapus kelas {{ $k->kelas }}?')">
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
                        Belum ada data kelas. Silakan tambah data baru.
                    </div>
                @endif
                
            </div>
        </div>
    </div>
</div>
@endsection
