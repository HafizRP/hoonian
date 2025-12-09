<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Laravel\Socialite\Facades\Socialite;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::with('roleData')->paginate(10);
        return view('admin.user.index', compact('users'));
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

    public function registerView()
    {
        return view('register');
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

    public function register(Request $request)
    {
        try {
            $errors = [
                'email.unique' => 'Email sudah terdaftar.',
                'email.required' => 'Email wajib diisi.',
                'email.email' => 'Format email tidak valid.',
            ];

            // 1️⃣ Validasi input
            $data = $request->validate([
                'name' => 'required',
                'email' => 'required|email|unique:users,email',
                'password' => 'required|min:6'
            ], $errors);

            // 2️⃣ Simpan user baru
            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'role' => 3,
                'password' => Hash::make($data['password']),
            ]);

            // 5️⃣ Redirect setelah login
            return redirect()->route('auth.login')
                ->withInput(['email' => $data['email']])
                ->with('success', 'Registrasi berhasil!');
        } catch (\Throwable $th) {
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

    // Redirect ke Google

    public function googleRedirect()
    {

        return Socialite::driver('google')->redirect();
    }

    // Callback dari Google

    public function googleCallback()
    {

        try {
            $socialUser = Socialite::driver('google')->user();
            // Cek apakah email sudah terdaftar
            $registeredUser = User::where('email', $socialUser->email)->first();

            if (!$registeredUser) {
                // 2️⃣ Simpan user baru
                $user = User::create([
                    'name' => $socialUser->name,
                    'email' => $socialUser->email,
                    'role' => 3,
                    'password' => Hash::make('default_password'),
                ]);

                // Login pengguna baru

                Auth::login($user);
            } else {

                // Jika email sudah terdaftar, langsung login
                Auth::logout(); // Logout pengguna

                Auth::login($registeredUser);
            }

            // Redirect ke halaman utama

            return redirect()->intended('/');
        } catch (\Exception $e) {
            // Redirect ke halaman utama jika terjadi kesalahan
            return redirect('/')->with('error', 'Terjadi kesalahan saat login dengan Google.');
        }
    }

    public function profile()
    {
        $user = User::findOrFail(Auth::user()->id);
        return view('user.profile', compact('user'));
    }

    public function updateProfile(Request $request)
    {
        try {
            $auth = Auth::user();

            // 1️⃣ Validasi input
            $data = $request->validate([
                'name' => 'required|string|max:255',

                // abaikan email user yg sedang login:
                'email' => 'required|email|max:255|unique:users,email,' . $auth->id,

                'bio' => 'nullable|string',
                'ig_url' => 'nullable|string',
                'wa_url' => 'nullable|string',
                'x_url' => 'nullable|string',
            ]);

            // 2️⃣ Update data user
            $user = User::find($auth->id);

            $user->update($data);

            // 3️⃣ Redirect balik
            return redirect()->back()->with('success', 'Profile updated successfully!');
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
