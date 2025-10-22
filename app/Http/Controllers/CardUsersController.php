<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Fungsi;
use App\Models\Absensi;
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
            ->whereDoesntHave('absensis', function ($query) {
                $query->where('status_id', 5);
            })
            ->filter($filters)
            ->orderBy('id', 'asc')
            ->paginate(10)
            ->withQueryString();

        $fungsis = Fungsi::all();

        return view('users', [
            'title' => 'Users Aktif',
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
        $from = request('from');

        // Tentukan title berdasarkan asal halaman
        $title = $from === 'alumni'
            ? 'Detail Alumni: ' . $user->name
            : 'Detail User: ' . $user->name;

        return view('users.card', [
            'title' => $title,
            'user' => $user,
            'from' => $from,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        $from = request('from');

        // Tentukan title berdasarkan asal halaman
        $title = $from === 'alumni'
            ? 'Edit Alumni: ' . $user->name
            : 'Edit User: ' . $user->name;

        return view('users.edit', [
            'title' => $title,
            'user' => $user,
            'fungsis' => Fungsi::all(),
            'from' => $from,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'nim' => [
                'required',
                'string',
                'min:6',
                'max:15',
                Rule::unique('users', 'nim')->ignore($user->id),
            ],
            'jurusan' => ['required', 'string', 'max:255'],
            'universitas' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'email:dns',
                'max:255',
                Rule::unique('users', 'email')->ignore($user->id),
            ],
            'telepon' => ['required', 'string', 'digits_between:9,13'],
            'alamat' => ['required', 'string', 'max:1000'],
            'tanggal_masuk' => ['required', 'date'],
            'tanggal_keluar' => ['required', 'date', 'after_or_equal:tanggal_masuk'],
            'jenis_kelamin' => ['required', Rule::in(['Laki-laki', 'Perempuan'])],
            'keahlian' => ['required', 'string', 'max:255'],
            'fungsi_id' => ['required', 'exists:fungsis,id'],
        ], [
            'required' => 'Field :attribute harus diisi.',
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
        ]);

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

        // === Logika Perpanjangan Masa Magang ===
        $today = Carbon::today();
        $tanggalKeluarBaru = Carbon::parse($validated['tanggal_keluar']);

        // Jika tanggal keluar diperpanjang (lebih besar dari hari ini)
        if ($tanggalKeluarBaru->greaterThan($today)) {
            // Hapus absensi dengan status selesai (5)
            Absensi::where('user_id', $user->id)
                ->where('status_id', 5)
                ->delete();
        }

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
