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
    
        if (is_array($users)) {
            $filteredUsers = array_map(function ($user) {
                return is_array($user) ? array_filter($user, function ($value) {
                    return !is_null($value);
                }) : $user;
            }, $users);
    
            return response()->json(['status' => 'success', 'data' => $filteredUsers], 200);
        } else {
            return response()->json(['status' => 'success', 'data' => []], 200);
        }
    }
    
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_lengkap' => 'required|string',
            'password' => 'required|string|min:1',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        $credentials = [
            'nama_lengkap' => $request->input('nama_lengkap'),
            'password' => $request->input('password'),
        ];

        $usersReference = $this->database->getReference('users');
        $usersSnapshot = $usersReference->orderByChild('nama_lengkap')
            ->equalTo($credentials['nama_lengkap'])
            ->getSnapshot();
        $users = $usersSnapshot->getValue();

        if (!$users) {
            return response()->json(['error' => 'Invalid credentials'], 401);
        }

        $user = array_shift($users);

        if (!Hash::check($credentials['password'], $user['password'])) {
            return response()->json(['error' => 'Invalid credentials'], 401);
        }
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
        $validator = Validator::make($request->all(), [
            'nama_lengkap' => 'required|max:100',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        $hashedPassword = bcrypt($request->password);

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
