<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function loginView()
    {
        return view('login');
    }

    public function login(Request $request)
    {
        try {
            // 1️⃣ Validasi input
            $credentials = $request->validate([
                'email' => 'required|email',
                'password' => 'required'
            ]);


            // 2️⃣ Coba login langsung pakai Auth::attempt()
            if (Auth::attempt($credentials)) {
                // 3️⃣ Regenerate session biar aman (session fixation protection)
                $request->session()->regenerate();

                // 4️⃣ Redirect ke halaman setelah login

                return redirect()->intended(route('main'));
            }

            // 5️⃣ Kalau gagal login, kembalikan ke form login dengan pesan error
            return back()->withErrors([
                'email' => 'Email atau password tidak sesuai.',
            ])->onlyInput('email');
        } catch (\Throwable $th) {
            // dd($th);
            throw $th;
        }
    }

    public function logout(Request $request)
    {
        Auth::logout(); // 1. Logout user

        $request->session()->invalidate(); // 2. Hapus session lama

        $request->session()->regenerateToken(); // 3. Bikin CSRF token baru

        return redirect()->route('login')->with('success', 'Anda berhasil logout.'); // 4. Redirect
    }
}
