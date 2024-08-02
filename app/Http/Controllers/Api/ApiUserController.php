<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Kreait\Firebase\Contract\Database;

class ApiUserController extends Controller
{
    protected $database;

    public function __construct(Database $database)
    {
        $this->database = $database;
    }

    public function index()
    {
        $usersReference = $this->database->getReference('users');
        $usersSnapshot = $usersReference->getSnapshot();
        $users = $usersSnapshot->getValue();
    
        // Pastikan $users adalah array
        if (is_array($users)) {
            $filteredUsers = array_map(function ($user) {
                // Pastikan $user adalah array sebelum menggunakan array_filter
                return is_array($user) ? array_filter($user, function ($value) {
                    return !is_null($value);
                }) : $user;
            }, $users);
    
            return response()->json(['status' => 'success', 'data' => $filteredUsers], 200);
        } else {
            // Jika $users bukan array, kembalikan array kosong
            return response()->json(['status' => 'success', 'data' => []], 200);
        }
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

        // Cek kredensial di Firebase
        $usersReference = $this->database->getReference('users');
        $usersSnapshot = $usersReference->orderByChild('nama_lengkap')
            ->equalTo($credentials['nama_lengkap'])
            ->getSnapshot();
        $users = $usersSnapshot->getValue();

        if (!$users) {
            return response()->json(['error' => 'Invalid credentials'], 401);
        }

        $user = array_shift($users);

        // Verifikasi password
        if (!Hash::check($credentials['password'], $user['password'])) {
            return response()->json(['error' => 'Invalid credentials'], 401);
        }

        // Buat token
        // Di sini Anda bisa menggunakan metode yang sesuai untuk membuat token autentikasi
        // Misalnya, menggunakan Laravel Sanctum atau Passport

        // Contoh menggunakan Laravel Sanctum:
        $userModel = User::where('nama_lengkap', $user['nama_lengkap'])->first();
        $tokenResult = $userModel->createToken('authToken');
        $token = $tokenResult->plainTextToken;

        return response()->json([
            'token' => $token,
            'user' => $user,
        ], 200);
    }

    public function store(Request $request)
    {
        // Validasi input
        $validator = Validator::make($request->all(), [
            'nama_lengkap' => 'required|max:100',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        // Hash password
        $hashedPassword = bcrypt($request->password);

        // Simpan data ke Firebase
        $newUser = [
            'nama_lengkap' => $request->nama_lengkap,
            'password' => $hashedPassword,
        ];

        $userReference = $this->database->getReference('users')->push($newUser);

        if ($userReference) {
            return response()->json(['status' => 'success', 'message' => 'User created successfully.'], 201);
        } else {
            return response()->json(['status' => 'error', 'message' => 'Failed to create user.'], 500);
        }
    }
}
