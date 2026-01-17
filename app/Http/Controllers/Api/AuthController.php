<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * Authentication Controller
 * Handle login dan authentication API
 */
class AuthController extends Controller
{
    /**
     * Login user
     * Endpoint: POST /api/login
     */
    public function login(Request $request)
    {
        // Validasi input
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6'
        ], [
            'email.required' => 'Email wajib diisi',
            'email.email' => 'Format email tidak valid',
            'password.required' => 'Password wajib diisi',
            'password.min' => 'Password minimal 6 karakter'
        ]);

        // Demo credentials untuk testing
        // Dalam production, seharusnya cek ke database users table
        $demoEmail = 'admin@siswa.com';
        $demoPassword = 'password';

        // Check credentials
        if ($request->email === $demoEmail && $request->password === $demoPassword) {
            // Generate simple token (dalam production gunakan Laravel Sanctum)
            $token = Str::random(60);

            // User data
            $user = [
                'id' => 1,
                'name' => 'Administrator',
                'email' => $demoEmail,
                'role' => 'admin'
            ];

            return response()->json([
                'success' => true,
                'message' => 'Login berhasil',
                'data' => [
                    'token' => $token,
                    'user' => $user
                ]
            ], 200);
        }

        // Login gagal
        return response()->json([
            'success' => false,
            'message' => 'Email atau password salah',
            'errors' => [
                'email' => ['Kredensial tidak cocok dengan data kami']
            ]
        ], 401);
    }

    /**
     * Logout user
     * Endpoint: POST /api/logout
     */
    public function logout(Request $request)
    {
        // Dalam production, hapus token dari database
        // Untuk demo, cukup return success

        return response()->json([
            'success' => true,
            'message' => 'Logout berhasil'
        ], 200);
    }

    /**
     * Get current authenticated user
     * Endpoint: GET /api/me
     */
    public function me(Request $request)
    {
        // Demo user data
        $user = [
            'id' => 1,
            'name' => 'Administrator',
            'email' => 'admin@siswa.com',
            'role' => 'admin'
        ];

        return response()->json([
            'success' => true,
            'message' => 'Data user berhasil diambil',
            'data' => $user
        ], 200);
    }
}
