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
use Illuminate\Support\Facades\Validator;

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
        Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'nim' => ['required', 'string', 'min:6', 'max:15', 'unique:users,nim'],
            'jurusan' => ['required', 'string', 'max:255'],
            'universitas' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email:dns', 'max:255', 'unique:users,email'],
            'telepon' => ['required', 'string', 'digits_between:9,13'],
            'alamat' => ['required', 'string', 'max:1000'],
            'tanggal_masuk' => ['required', 'date'],
            'tanggal_keluar' => ['required', 'date', 'after_or_equal:tanggal_masuk'],
            'jenis_kelamin' => ['required', Rule::in(['Laki-laki', 'Perempuan'])],
            'keahlian' => ['required', 'string', 'max:255'],
            'fungsi_id' => ['required', 'exists:fungsis,id'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ], [
            'required' => 'Field :attribute harus diisi.',

            // Spesifik
            'name.max' => 'Nama tidak boleh lebih dari :max karakter.',
            'nim.string' => 'NIM harus berupa kombinasi huruf dan/atau angka.',
            'nim.min' => 'NIM harus memiliki minimal :min karakter.',
            'nim.max' => 'NIM tidak boleh lebih dari :max karakter.',
            'nim.unique' => 'NIM ini sudah terdaftar.',
            'jurusan.max' => 'Jurusan tidak boleh lebih dari :max karakter.',
            'universitas.max' => 'Universitas tidak boleh lebih dari :max karakter.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email ini sudah terdaftar.',
            'telepon.digits_between' => 'Nomor telepon harus terdiri dari :min sampai :max digit.',
            'alamat.max' => 'Alamat tidak boleh lebih dari :max karakter.',
            'tanggal_masuk.date' => 'Tanggal masuk harus berupa tanggal yang valid.',
            'tanggal_keluar.date' => 'Tanggal keluar harus berupa tanggal yang valid.',
            'tanggal_keluar.after_or_equal' => 'Tanggal keluar tidak boleh sebelum tanggal masuk.',
            'jenis_kelamin.in' => 'Jenis kelamin harus diisi dengan Laki-laki atau Perempuan.',
            'keahlian.max' => 'Keahlian tidak boleh lebih dari :max karakter.',
            'fungsi_id.exists' => 'Fungsi yang dipilih tidak tersedia.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
        ], [
            'name' => 'Nama',
            'nim' => 'NIM/NISN',
            'jurusan' => 'Jurusan',
            'universitas' => 'Universitas',
            'email' => 'Email',
            'telepon' => 'Nomor Telepon',
            'alamat' => 'Alamat',
            'tanggal_masuk' => 'Tanggal Masuk',
            'tanggal_keluar' => 'Tanggal Keluar',
            'jenis_kelamin' => 'Jenis Kelamin',
            'keahlian' => 'Keahlian',
            'fungsi_id' => 'Fungsi',
            'password' => 'Password',
        ])->validate();

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
