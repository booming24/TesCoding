<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Kreait\Firebase\Contract\Database;
use App\Http\Controllers\Controller;

class ApiFirebaseController extends Controller
{
    protected $database;

    public function __construct(Database $database)
    {
        $this->database = $database;
    }

    public function createUserTable()
    {
        $usersReference = $this->database->getReference('users');
        
        $usersReference->set([
            '1' => [
                'nama_lengkap' => 'Muhammad Irfan Thoriq',
                'password' => bcrypt('password123'),
            ],
            '2' => [
                'nama_lengkap' => 'Ipane',
                'password' => bcrypt('password456'),
            ]
        ]);

        return response()->json(['status' => 'success', 'message' => 'User table created.'], 200);
    }

    public function addUser(Request $request)
    {
        $request->validate([
            'nama_lengkap' => 'required|max:100',
            'password' => 'required',
        ]);

        $hashedPassword = bcrypt($request->password);

        $newUser = [
            'nama_lengkap' => $request->nama_lengkap,
            'password' => $hashedPassword,
        ];

        $userReference = $this->database->getReference('users')->push($newUser);

        return response()->json(['status' => 'success', 'message' => 'User added successfully.'], 201);
    }
}
