<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Fungsi;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;

class CardUsersController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $filters = request()->only(['search', 'fungsi', 'jenis_kelamin']);

        $users = User::with(['fungsi'])
            ->where('is_admin', 0) // hanya ambil user bukan admin
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
            'nim' => [
                'required',
                'numeric',
                'digits_between:6,15',
                Rule::unique(User::class, 'nim')->ignore($user->id),
            ],
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
            'nim.required' => 'NIM wajib diisi.',
            'nim.numeric' => 'NIM harus berupa angka.',
            'nim.digits_between' => 'NIM harus terdiri dari antara 6 sampai 15 digit.',
            'nim.unique' => 'NIM ini sudah digunakan.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email sudah digunakan.',
            // ... pesan error lain sama seperti sebelumnya
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
            'nim' => $validated['nim'],
            'email' => $validated['email'],
            'jurusan' => $validated['jurusan'],
            'universitas' => $validated['universitas'],
            'telepon' => $validated['telepon'],
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
        if (auth()->user()->slug === $user->slug) {
            return redirect()->route('users.index')->with('error', 'Kamu tidak bisa menghapus diri sendiri.');
        }

        if ($user->is_admin) {
            return redirect()->route('users.index')->with('error', 'Tidak bisa menghapus sesama admin.');
        }

        // Backup dulu ke tabel deleted_users
        DB::table('deleted_users')->insert([
            'original_user_id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'slug' => $user->slug,
            'is_admin' => $user->is_admin,
            'fungsi_id' => $user->fungsi_id,
            'tanggal_keluar' => $user->tanggal_keluar,
            'full_data' => json_encode($user),
            'deleted_by_admin_id' => auth()->id(),
            'deleted_by_admin_at' => now('Asia/Makassar'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Hapus user dari tabel users
        $user->delete();

        return redirect()->route('users.index')->with('success', 'User berhasil dihapus dan dicatat di log.');
    }
}
