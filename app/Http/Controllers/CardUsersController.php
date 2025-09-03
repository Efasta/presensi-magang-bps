<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Fungsi;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CardUsersController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $filters = request()->only(['search', 'fungsi', 'jenis_kelamin']);

        $users = User::with(['fungsi'])
            ->filter($filters)
            ->orderBy('id', 'asc')
            ->paginate(10)
            ->withQueryString();

        $fungsis = Fungsi::all();

        return view('users', [
            'title' => 'Users',
            'users' => $users,
            'fungsis' => $fungsis
        ]);
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
    public function show(User $user)
    {
        return view('users.card', ['title' => 'Detail User: ' . $user['name'], 'user' => $user]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        return view('users.edit', [
            'title' => 'Edit User: ' . $user['name'],
            'user' => $user,
            'fungsis' => Fungsi::all()
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        // Validasi data input
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'lowercase',
                'email:dns',
                'max:255',
                Rule::unique(User::class)->ignore($user->id),
            ],
            'jurusan' => ['required', 'string', 'max:255'],
            'universitas' => ['required', 'string', 'max:255'],
            'telepon' => ['required', 'string', 'digits_between:9,13'],
            'alamat' => ['required', 'string', 'max:255'],
            'tanggal_masuk' => ['required', 'date'],
            'tanggal_keluar' => ['required', 'date'],
            'keahlian' => ['required', 'string', 'max:255'],
            'jenis_kelamin' => ['required', Rule::in(['Laki-laki', 'Perempuan'])],
            'fungsi_id' => ['required', 'exists:fungsis,id'],
        ], [
            // Custom messages (sama kayak `ProfileUpdateRequest`)
            'name.required' => 'Nama lengkap wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email sudah digunakan.',
            'jurusan.required' => 'Jurusan wajib diisi.',
            'universitas.required' => 'Universitas wajib diisi.',
            'telepon.required' => 'Nomor Telepon wajib diisi.',
            'telepon.digits_between' => 'Nomor telepon harus antara 9 sampai 13 digit.',
            'alamat.required' => 'Alamat wajib diisi.',
            'tanggal_masuk.required' => 'Tanggal masuk wajib diisi.',
            'tanggal_keluar.required' => 'Tanggal keluar wajib diisi.',
            'tanggal_keluar.date' => 'Tanggal keluar tidak valid.',
            'keahlian.required' => 'Keahlian wajib diisi.',
            'jenis_kelamin.required' => 'Jenis kelamin wajib dipilih.',
            'fungsi_id.required' => 'Fungsi wajib dipilih.',
            'fungsi_id.exists' => 'Fungsi yang dipilih tidak valid.',
        ]);

        // Validasi tambahan: tanggal_keluar tidak boleh sebelum tanggal_masuk
        if (
            isset($validated['tanggal_masuk'], $validated['tanggal_keluar']) &&
            $validated['tanggal_keluar'] < $validated['tanggal_masuk']
        ) {
            return back()
                ->withErrors(['tanggal_keluar' => 'Tanggal keluar tidak boleh sebelum tanggal masuk.'])
                ->withInput();
        }

        // Update data user
        $user->update([
            'name' => $validated['name'],
            'slug' => Str::slug($validated['name']),
            'email' => $validated['email'],
            'jurusan' => $validated['jurusan'],
            'universitas' => $validated['universitas'],
            'no_telp' => $validated['no_telp'],
            'alamat' => $validated['alamat'],
            'tanggal_masuk' => $validated['tanggal_masuk'],
            'tanggal_keluar' => $validated['tanggal_keluar'],
            'keahlian' => $validated['keahlian'],
            'jenis_kelamin' => $validated['jenis_kelamin'],
            'fungsi_id' => $validated['fungsi_id'],
        ]);

        return redirect()->route('users.index')->with('success', 'Data user berhasil diperbarui.');
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        // Contoh: Cegah user menghapus diri sendiri
        if (auth()->user()->slug === $user->slug) {
            return redirect()->route('users.index')->with('error', 'Kamu tidak bisa menghapus diri sendiri.');
        }

        $user->delete();

        return redirect()->route('users.index')->with('success', 'User berhasil dihapus.');
    }
}
