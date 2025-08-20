<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Models\Fungsi;
use Illuminate\View\View;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\RedirectResponse;
use Illuminate\Auth\Events\Registered;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        $fungsiList = Fungsi::all();
        return view('auth.register', compact('fungsiList'));
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'nim' => ['required', 'numeric', 'digits_between:8,15', 'unique:users,nim'],
            'jurusan' => ['required', 'string', 'max:255'],
            'universitas' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email:dns', 'max:255', 'unique:users,email'],
            'telepon' => ['required', 'string', 'digits_between:9,13'],
            'alamat' => ['required', 'string', 'max:1000'],
            'tanggal_masuk' => ['required', 'date'],
            'tanggal_keluar' => ['required', 'date', 'after_or_equal:tanggal_masuk'],
            'jenis_kelamin' => ['required', Rule::in(['Laki-laki', 'Perempuan'])],
            'keahlian' => ['required', 'string', 'max:255'],
            'fungsi_id' => ['required', 'exists:fungsis,id'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name' => $request->name,
            'nim' => $request->nim,
            'jurusan' => $request->jurusan,
            'universitas' => $request->universitas,
            'email' => $request->email,
            'telepon' => $request->telepon,
            'alamat' => $request->alamat,
            'tanggal_masuk' => $request->tanggal_masuk,
            'tanggal_keluar' => $request->tanggal_keluar,
            'jenis_kelamin' => $request->jenis_kelamin,
            'keahlian' => $request->keahlian,
            'fungsi_id' => $request->fungsi_id,
            'password' => Hash::make($request->password),
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect(route('dashboard', absolute: false));
    }
}
