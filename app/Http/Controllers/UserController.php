<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Property;
use App\Models\Transaction;
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
        // Gunakan orderBy
        $users = User::with('roleData')->orderBy('id', 'ASC')->get();
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
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
            'role' => 'required'
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'profile_img' => null // Optional: add default or handle upload
        ]);

        return redirect()->back()->with('success', 'User created successfully.');
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
        $user = User::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'role' => 'required'
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role
        ]);

        return redirect()->back()->with('success', 'User updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->back()->with('success', 'User deleted successfully.');
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
            $user = Auth::user();

            // 1️⃣ Validasi input (tambahkan validasi untuk image)
            $data = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|max:255|unique:users,email,' . $user->id,
                'bio' => 'nullable|string',
                'ig_url' => 'nullable|string',
                'wa_url' => 'nullable|string',
                'x_url' => 'nullable|string',
                'profile_img' => 'nullable|image|mimes:jpeg,png,jpg|max:2048', // max 2MB
            ]);

            // dd($data);

            // 2️⃣ Handle Image Upload
            if ($request->hasFile('profile_img')) {
                // Hapus foto lama jika ada dan file-nya eksis di folder
                if ($user->profile_img && file_exists(public_path($user->profile_img))) {
                    unlink(public_path($user->profile_img));
                }

                // Buat nama file unik
                $imageName = time() . '-' . $user->id . '.' . $request->profile_img->extension();

                // Pindahkan file ke folder public/uploads/profiles
                $request->profile_img->move(public_path('uploads/profiles'), $imageName);

                // Simpan path-nya ke array data untuk diupdate
                $data['profile_img'] = 'uploads/profiles/' . $imageName;
            }


            // 3️⃣ Update data user
            $user->update($data);

        } catch (\Throwable $th) {
            return redirect()->back()->with('error', 'Something went wrong: ' . $th->getMessage());
        }
    }

    public function adminDashboard()
    {
        // 1. Statistics
        $totalUsers = User::where('role', 'user')->count();
        $totalProperties = Property::count();
        $totalTransactions = Transaction::count();

        // 2. Latest Properties (Top 3)
        $latestProperties = Property::latest()->take(3)->get();

        // 3. New Customers (Latest 3 Users)
        $newCustomers = User::where('role', 'user')->latest()->take(3)->get();

        // 4. Recent Transactions (Top 5)
        $recentTransactions = Transaction::with(['user', 'property'])->latest()->take(5)->get();

        return view('admin.index', compact(
            'totalUsers',
            'totalProperties',
            'totalTransactions',
            'latestProperties',
            'newCustomers',
            'recentTransactions'
        ));
    }
}
