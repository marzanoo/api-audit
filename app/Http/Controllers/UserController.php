<?php

namespace App\Http\Controllers;

use App\Models\Karyawan;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\CssSelector\Parser\Shortcut\HashParser;

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
            'username' => 'required',
            'email' => 'required',
            'password' => 'nullable|min:8',
            'role' => 'required',
        ]);

        $user = User::Where('email', $request->email)->where('id', '!=', $id)->exists();
        if ($user) {
            return redirect()->route('edit-user', $id)->with(['user_error' => 'Email telah digunakan, Mohon gunakan email lain']);
        }

        $user = User::find($id);
        $user->name = $request->name;
        $user->username = $request->username;
        $user->role = $request->role;
        $user->email = $request->email;
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }
        $user->save();

        return redirect()->route('users')->with(['user_success' => 'User telah berhasil diubah']);
    }

    public function addUser()
    {
        return view('admin.konfigurasi.user.add-user');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nik' => 'required',
            'name' => 'required',
            'username' => 'required',
            'role' => 'required',
            'email' => 'required',
            'password' => 'required|min:8'
        ]);

        $user = User::Where('email', $request->email)->exists();
        if ($user) {
            return redirect()->route('add-user')->with(['user_error' => 'Email telah digunakan, Mohon gunakan email lain']);
        }

        $user = User::Where('username', $request->username)->exists();
        if ($user) {
            return redirect()->route('add-user')->with(['user_error' => 'Username telah digunakan, Mohon gunakan username lain']);
        }

        $user = User::Where('nik', $request->nik)->exists();
        if ($user) {
            return redirect()->route('add-user')->with(['user_error' => 'NIK telah digunakan, Mohon gunakan NIK lain']);
        }

        $karyawan = Karyawan::where('emp_id', $request->nik)->first();

        if (!$karyawan) {
            return redirect()->route('add-user')->with(['user_error' => 'NIK tidak ditemukan']);
        }

        User::create([
            'nik' => $request->nik,
            'name' => $request->name,
            'username' => $request->username,
            'role' => $request->role,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'email_verified_at' => Carbon::now(),
        ]);

        return redirect()->route('users')->with(['user_success' => 'User telah berhasil ditambahkan']);
    }

    public function destroy($id)
    {
        $user = User::find($id);
        $user->delete();
        return redirect()->route('users')->with(['user_success' => 'User telah berhasil dihapus']);
    }

    public function resetDeviceId($id)
    {
        $user = User::find($id);
        $user->device_id = null;
        $user->save();

        return redirect()->route('users')->with(['user_success' => "Device Id " . $user->name . " berhasil direset"]);
    }
}
