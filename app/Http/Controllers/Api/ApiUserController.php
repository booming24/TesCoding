<?php

namespace App\Http\Controllers\Api;

use App\Models\ProgramStudi;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;



class ApiUserController extends Controller
{
    public function index()
    {
        $users = User::all()->map(function ($user) {
            return array_filter($user->toArray(), function ($value) {
                return !is_null($value);
            });
        });

        return response()->json(['status' => 'success', 'data' => $users], 200);
    }

    public function login(Request $request)
    {
        // Validasi input
        $validator = Validator::make($request->all(), [
            'nama_lengkap' => 'required|string',
            'password' => 'required|string|min:1',
        ]);
    
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }
    
        // Kredensial untuk autentikasi
        $credentials = [
            'nama_lengkap' => $request->input('nama_lengkap'),
            'password' => $request->input('password'),
        ];
    
        // Log kredensial untuk debugging
        \Log::info('Attempting login with:', [
            'nama_lengkap' => $credentials['nama_lengkap'],
            'password' => $credentials['password'], // Jangan log password dalam produksi
        ]);
    
        // Cek kredensial
        if (!Auth::attempt($credentials)) {
            \Log::warning('Login failed for:', ['nama_lengkap' => $credentials['nama_lengkap']]);
            return response()->json(['error' => 'Invalid credentials'], 401);
        }
    
        // Ambil pengguna yang terautentikasi
        $user = Auth::user();
    
        // Buat token
        $tokenResult = $user->createToken('authToken');
        $token = $tokenResult->plainTextToken;
    
        return response()->json([
            'token' => $token,
            'user' => $user,
        ], 200);
    }
    
}
