<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function show(): View
    {
        $user = auth()->user() ?? (object)[
            'name' => 'Pengguna Demo',
            'email' => 'demo@sibi.id',
            'role' => (object)['value' => 'contributor', 'label' => fn() => 'Kontributor'],
            'institution' => 'Universitas Indonesia',
            'phone' => '081234567890',
        ];

        return view('pages.profile.index', compact('user'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'institution' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:50',
        ]);

        if (auth()->check()) {
            auth()->user()->update($request->only(['name', 'institution', 'phone']));
        }

        return back()->with('success', 'Profil berhasil diperbarui!');
    }
}
