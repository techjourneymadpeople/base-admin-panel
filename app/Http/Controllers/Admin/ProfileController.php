<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        $user = $request->user();
        $user->load('roles');
        $allPermissions = $user->getAllPermissions();

        return view('admin.profile.edit', compact('user', 'allPermissions'));
    }

    /**
     * Update the user's profile information (Name & Email).
     */
    public function update(Request $request): RedirectResponse
    {
        $user = $request->user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
        ]);

        $user->name = trim($validated['name']);
        $user->email = strtolower(trim($validated['email']));
        $user->save();

        return redirect()->route('admin.profile.edit')
            ->with('success', 'Informasi profil Anda berhasil diperbarui!');
    }

    /**
     * Update the user's password.
     */
    public function updatePassword(Request $request): RedirectResponse
    {
        $user = $request->user();

        $validated = $request->validate([
            'current_password' => ['required', 'current_password:web'],
            'password' => ['required', 'string', Password::min(8)->mixedCase()->numbers()->symbols(), 'confirmed'],
        ], [
            'current_password.current_password' => 'Kata sandi saat ini yang Anda masukkan tidak sesuai.',
            'password.confirmed' => 'Konfirmasi kata sandi baru tidak cocok.',
            'password.min' => 'Kata sandi baru minimal harus 8 karakter.',
        ]);

        $user->password = Hash::make($validated['password']);
        $user->save();

        return redirect()->route('admin.profile.edit')
            ->with('success', 'Kata sandi akun Anda berhasil diperbarui!');
    }
}
