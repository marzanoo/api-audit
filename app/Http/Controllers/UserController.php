<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        $users = User::all();
        return view('admin.konfigurasi.user.index', compact('users'));
    }

    public function editUser($id)
    {
        $user = User::findOrFail($id);
        return view('admin.konfigurasi.user.edit-user', compact('user'));
    }

    public function update($id, Request $request)
    {
        $request->validate([
            'name' => 'required',
            'role' => 'required',
            'email' => 'required'
        ]);

        $user = User::Where('email', $request->email)->where('id', '!=', $id)->exists();
        if ($user) {
            return redirect()->route('edit-user', $id)->with(['user_error' => 'Email telah digunakan, Mohon gunakan email lain']);
        }

        $user = User::find($id);
        $user->name = $request->name;
        $user->role = $request->role;
        $user->email = $request->email;
        $user->save();

        return redirect()->route('users')->with(['user_success' => 'User telah berhasil diubah']);
    }
}
