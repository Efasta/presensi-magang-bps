<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\View\View;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Redirect;
use App\Http\Requests\ProfileUpdateRequest;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
{
    $validated = $request->validated();

    // Simpan foto lama sebelum diubah
    // $oldFoto = $request->user()->foto;

    if ($request->name !== $request->user()->name) {
        $slug = Str::slug($request->name);
        $originalSlug = $slug;
        $i = 1;

        while (User::where('slug', $slug)->where('id', '!=', $request->user()->id)->exists()) {
            $slug = $originalSlug . '-' . $i++;
        }

        $request->user()->slug = $slug;
    }

    $request->user()->fill($request->safe()->except([
        'nim',
        'jenis_kelamin',
        'tanggal_masuk',
        'slug'
    ]));

    if ($request->user()->isDirty('email')) {
        $request->user()->email_verified_at = null;
    }

    // ========== UPLOAD FOTO (BARU) ==========
    if ($request->foto) {
        if (!empty($request->user()->foto)) {
            Storage::disk('public')->delete($request->user()->foto);
        }

        $newFileName = Str::after($request->foto, 'tmp/');
        Storage::disk('public')->move($request->foto, "img/$newFileName");

        $validated['foto'] = "img/$newFileName";
    }

    // ========== UPLOAD FOTO (LAMA - dikomentari) ==========
    /*
    if ($request->hasFile('foto')) {
        // Hapus foto lama dulu
        if (!empty($oldFoto)) {
            Storage::disk('public')->delete($oldFoto);
        }

        // Simpan foto baru dan tambahkan ke array validated
        $path = $request->file('foto')->store('img', 'public');
        $validated['foto'] = $path;
    }
    */

    // Simpan perubahan ke database (termasuk foto baru jika ada)
    $request->user()->update($validated);

    return Redirect::route('profile.edit')->with('status', 'profile-updated');
}

    public function upload(Request $request)
    {
        if ($request->hasFile('foto')) {
            $path = $request->file('foto')->store('tmp', 'public');
        }

        return $path;
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
