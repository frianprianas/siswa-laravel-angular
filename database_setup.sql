-- =====================================================
-- Script SQL untuk membuat database db_siswa
-- File ini bisa dijalankan di phpMyAdmin atau MySQL CLI
-- =====================================================

-- Membuat database baru dengan nama db_siswa
-- IF NOT EXISTS memastikan database hanya dibuat jika belum ada
CREATE DATABASE IF NOT EXISTS db_siswa 
CHARACTER SET utf8mb4 
COLLATE utf8mb4_unicode_ci;

-- Pilih database yang baru dibuat
USE db_siswa;

-- Pesan konfirmasi
SELECT 'Database db_siswa berhasil dibuat!' AS Status;

-- =====================================================
-- CATATAN PENTING:
-- =====================================================
-- Setelah menjalankan script ini, jangan lupa jalankan:
-- php artisan migrate
-- 
-- Migration akan membuat tabel siswas dengan struktur:
-- - id (Primary Key, Auto Increment)
-- - nis (VARCHAR 20, UNIQUE)
-- - nama (VARCHAR 100)
-- - tempat (VARCHAR 100)
-- - tgl_lahir (DATE)
-- - jenis_kelamin (ENUM 'L','P')
-- - created_at (TIMESTAMP)
-- - updated_at (TIMESTAMP)
-- =====================================================
