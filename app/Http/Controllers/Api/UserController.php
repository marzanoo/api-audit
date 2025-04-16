<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Karyawan;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $users = User::all();
        return response()->json([
            'message' => 'User berhasil diambil',
            'data' => $users
        ]);
    }

    public function show($id)
    {
        $user = User::find($id);
        return response()->json([
            'message' => 'User berhasil diambil',
            'data' => $user
        ]);
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

        $user = User::where('email', $request->email)->exists();
        if ($user) {
            return response()->json([
                'message' => 'Email telah digunakan, Mohon gunakan email lain'
            ], 400);
        }

        $user = User::Where('username', $request->username)->exists();
        if ($user) {
            return response()->json([
                'message' => 'Username telah digunakan, Mohon gunakan username lain'
            ], 400);
        }

        $user = User::Where('nik', $request->nik)->exists();
        if ($user) {
            return response()->json([
                'message' => 'NIK telah digunakan, Mohon gunakan NIK lain'
            ], 400);
        }

        $karyawan = Karyawan::where('emp_id', $request->nik)->first();

        if (!$karyawan) {
            return response()->json([
                'message' => 'NIK tidak ditemukan'
            ], 404);
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

        return response()->json([
            'message' => 'User berhasil ditambahkan',
            'data' => User::where('email', $request->email)->first()
        ]);
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
            return response()->json([
                'message' => 'Email telah digunakan, Mohon gunakan email lain'
            ], 400);
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

        return response()->json([
            'message' => 'User berhasil diubah',
            'data' => $user
        ]);
    }

    public function destroy($id)
    {
        $user = User::find($id);
        $user->delete();
        return response()->json([
            'message' => 'User berhasil dihapus'
        ]);
    }

    public function resetDeviceId($id)
    {
        $user = User::find($id);
        $user->device_id = null;
        $user->save();

        return response()->json(['message' => 'Device Id ' . $user->name . ' berhasil direset']);
    }
}
