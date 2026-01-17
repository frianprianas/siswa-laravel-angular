{{--
    File: app.blade.php
    Layout utama aplikasi dengan navigasi menu
    Semua halaman akan menggunakan layout ini
--}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Sistem Informasi Siswa')</title>
    
    {{-- Bootstrap CSS --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    {{-- Font Awesome --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    {{-- Custom CSS --}}
    <style>
        /* Styling untuk navbar */
        .navbar {
            box-shadow: 0 2px 4px rgba(0,0,0,.1);
        }
        
        /* Active menu item */
        .navbar-nav .nav-link.active {
            background-color: rgba(255,255,255,.2);
            border-radius: 5px;
        }
        
        /* Hover effect untuk menu */
        .navbar-nav .nav-link:hover {
            background-color: rgba(255,255,255,.1);
            border-radius: 5px;
        }
        
        /* Content area */
        .content-wrapper {
            min-height: calc(100vh - 120px);
            padding-top: 20px;
            padding-bottom: 20px;
        }
        
        /* Footer */
        .footer {
            background-color: #f8f9fa;
            padding: 20px 0;
            margin-top: 40px;
            border-top: 1px solid #dee2e6;
        }
        
        /* Card hover effect */
        .card {
            transition: transform 0.2s;
        }
        
        .card:hover {
            transform: translateY(-5px);
        }
    </style>
    
    {{-- Stack untuk CSS tambahan dari child view --}}
    @stack('styles')
</head>
<body>
    
    {{-- Navbar / Menu Navigasi --}}
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            {{-- Brand / Logo --}}
            <a class="navbar-brand" href="/">
                <i class="fas fa-graduation-cap"></i> 
                <strong>SISTEM INFORMASI SISWA</strong>
            </a>
            
            {{-- Tombol Toggle untuk Mobile --}}
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            {{-- Menu Navigasi --}}
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    
                    {{-- Menu Siswa --}}
                    <li class="nav-item">
                        {{-- 
                            request()->routeIs('siswa.*') akan return true jika route dimulai dengan 'siswa.'
                            Digunakan untuk menandai menu aktif
                        --}}
                        <a class="nav-link {{ request()->routeIs('siswa.*') ? 'active' : '' }}" 
                           href="{{ route('siswa.index') }}">
                            <i class="fas fa-users"></i> Data Siswa
                        </a>
                    </li>
                    
                    {{-- Menu Kelas --}}
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('kelas.*') ? 'active' : '' }}" 
                           href="{{ route('kelas.index') }}">
                            <i class="fas fa-door-open"></i> Data Kelas
                        </a>
                    </li>
                    
                    {{-- Menu Jurusan --}}
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('jurusan.*') ? 'active' : '' }}" 
                           href="{{ route('jurusan.index') }}">
                            <i class="fas fa-book"></i> Data Jurusan
                        </a>
                    </li>
                    
                    {{-- Dropdown Info --}}
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" 
                           role="button" data-bs-toggle="dropdown">
                            <i class="fas fa-info-circle"></i> Info
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li>
                                <a class="dropdown-item" href="#">
                                    <i class="fas fa-question-circle"></i> Bantuan
                                </a>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <a class="dropdown-item" href="#">
                                    <i class="fas fa-info"></i> Tentang
                                </a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    
    {{-- Content Area --}}
    <div class="content-wrapper">
        <div class="container">
            
            {{-- 
                Flash Messages
                Menampilkan pesan sukses atau error dari controller
            --}}
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            
            {{-- 
                @yield('content')
                Tempat untuk konten dari child view
                Setiap halaman akan mengisi bagian ini dengan konten masing-masing
            --}}
            @yield('content')
        </div>
    </div>
    
    {{-- Footer --}}
    <footer class="footer">
        <div class="container text-center">
            <p class="mb-0 text-muted">
                <i class="fas fa-heart text-danger"></i> 
                Dibuat dengan Laravel {{ app()->version() }} 
                &copy; {{ date('Y') }} Sistem Informasi Siswa
            </p>
        </div>
    </footer>
    
    {{-- Bootstrap JavaScript --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    {{-- Stack untuk JavaScript tambahan dari child view --}}
    @stack('scripts')
    
</body>
</html>
