<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends FormRequest
{
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
            'jurusan' => ['nullable', 'string', 'max:255'],
            'universitas' => ['nullable', 'string', 'max:255'],
            'telepon' => ['nullable', 'string', 'digits_between:9,13'],
            'alamat' => ['nullable', 'string', 'max:255'],
            'tanggal_keluar' => ['nullable', 'date'],
            'keahlian' => ['nullable', 'string', 'max:255'],
            'jenis_kelamin' => ['required', Rule::in([$this->user()->jenis_kelamin])],
            // 'foto' => 'image|max:1000',

            // Protected fields - make read-only
            'nim' => ['prohibited'],
            'tanggal_masuk' => ['prohibited'],
        ];
    }

    public function messages(): array
    {
        return [
            'foto.image' => 'File yang diunggah harus berupa gambar.',
            'foto.max' => 'Ukuran gambar maksimal adalah 1 MB.',
            'foto.uploaded' => 'Gagal mengunggah gambar. Pastikan ukuran file tidak melebihi batas maksimum dan koneksi stabil.',
        ];
    }
}
