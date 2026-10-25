<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit()
    {
        return view('profile.edit');
    }

    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->update($request->validated());

        return redirect()->route('profile.edit')->with('status', 'Profil diperbarui.');
    }

    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ], [
            'password.required'         => 'Password wajib diisi.',
            'password.current_password' => 'Password salah.',
        ]);

        $user = $request->user();

        Auth::logout();

        // Urutan penting: dompet dan kategori dilindungi RESTRICT,
        // jadi transaksi harus dihapus lebih dulu
        DB::transaction(function () use ($user) {
            $user->transactions()->delete();
            $user->budgets()->delete();
            $user->wallets()->delete();
            $user->categories()->delete();
            $user->delete();
        });

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
