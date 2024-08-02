<?php

namespace App\Http\Controllers;

use App\Models\ProgramStudi;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        $users = User::all();
        return view('users.index', compact('users'));
    }

    public function create()
    {
        return view('users.create');    
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_lengkap' => 'required|max:100',
            'password' => 'required',
        ]);

        $user = new User([
            'nama_lengkap' => $request->nama_lengkap,
            'password' => bcrypt($request->password),
        ]);

        $user->save();

        return redirect()->route('users.index')->with('success', 'User created successfully.');
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('users.edit', compact('user'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_lengkap' => 'required|max:100',
            'password' => 'nullable',
        ]);

        $user = User::findOrFail($id);

        $user->nama_lengkap = $request->nama_lengkap;
        if ($request->password) {
            $user->password = bcrypt($request->password);
        }

        $user->save();

        return redirect()->route('users.index')->with('success', 'User updated successfully.');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->route('users.index')->with('success', 'User deleted successfully.');
    }
}
