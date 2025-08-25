<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends FormRequest
{
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $tanggalMasuk = $this->user()->tanggal_masuk;
            $tanggalKeluar = $this->input('tanggal_keluar');

            if ($tanggalKeluar && $tanggalMasuk && $tanggalKeluar < $tanggalMasuk) {
                $validator->errors()->add('tanggal_keluar', 'Tanggal keluar tidak boleh sebelum tanggal masuk.');
            }
        });
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'lowercase',
                'email:dns',
                'max:255',
                Rule::unique(User::class)->ignore($this->user()->id),
            ],
            // Optional fields - editable
            'jurusan' => ['required', 'string', 'max:255'],
            'universitas' => ['required', 'string', 'max:255'],
            'telepon' => ['required', 'string', 'digits_between:9,13'],
            'alamat' => ['required', 'string', 'max:255'],
            'tanggal_keluar' => ['required', 'date'],
            'keahlian' => ['required', 'string', 'max:255'],
            'jenis_kelamin' => ['required', Rule::in([$this->user()->jenis_kelamin])],
            'fungsi_id' => ['required', 'exists:fungsis,id'],
            // 'foto' => 'image|max:1000',

            // Protected fields - make read-only
            'nim' => ['prohibited'],
            'tanggal_masuk' => ['prohibited'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Nama lengkap wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'jurusan.required' => 'Jurusan wajib diisi.',
            'universitas.required' => 'Universitas wajib diisi.',
            'telepon.required' => 'Nomor Telepon wajib diisi.',
            'alamat.required' => 'Alamat wajib diisi.',
            'keahlian.required' => 'Keahlian wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email sudah digunakan.',
            'jurusan.string' => 'Jurusan harus mengandung teks.',
            'universitas.string' => 'Universitas harus mengandung teks.',
            'telepon.digits_between' => 'Nomor telepon harus antara 9 sampai 13 digit.',
            'alamat.string' => 'Alamat harus mengandung teks.',
            'tanggal_keluar.date' => 'Tanggal keluar tidak valid.',
            'tanggal_keluar.after_or_equal' => 'Tanggal keluar tidak boleh sebelum tanggal masuk.',
            'keahlian.string' => 'Keahlian harus mengandung teks.',
            'jenis_kelamin.required' => 'Jenis kelamin wajib dipilih.',
            'fungsi_id.required' => 'Fungsi wajib dipilih.',
            'fungsi_id.exists' => 'Fungsi yang dipilih tidak valid.',
            'nim.prohibited' => 'NIM tidak boleh diubah.',
            'tanggal_masuk.prohibited' => 'Tanggal masuk tidak boleh diubah.',

            // Pesan khusus untuk upload foto
            'foto.image' => 'File yang diunggah harus berupa gambar.',
            'foto.max' => 'Ukuran gambar maksimal adalah 1 MB.',
            'foto.uploaded' => 'Gagal mengunggah gambar. Pastikan ukuran file tidak melebihi batas maksimum dan koneksi stabil.',
        ];
    }
}
