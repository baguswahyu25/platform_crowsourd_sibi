<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function show(): View
    {
        $user = auth()->user();

        return view('pages.profile.index', compact('user'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'institution' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:50',
            'avatar' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ], [
            'avatar.image' => 'File foto profil harus berupa gambar.',
            'avatar.mimes' => 'Format foto profil yang diperbolehkan adalah JPG, JPEG, PNG, atau WEBP.',
            'avatar.max' => 'Ukuran maksimal foto profil adalah 2 MB.',
        ]);

        $user = auth()->user();

        if ($user) {
            $user->name = $request->name;
            $user->institution = $request->institution;
            $user->phone = $request->phone;

            if ($request->hasFile('avatar')) {
                $file = $request->file('avatar');
                $filename = 'avatar_' . $user->id . '_' . time() . '.' . $file->getClientOriginalExtension();

                // Simpan foto baru terlebih dahulu di storage/app/public/avatars/
                $path = $file->storeAs('avatars', $filename, 'public');

                // Hapus foto lama jika ada
                if (!empty($user->avatar)) {
                    $oldCleanPath = ltrim(str_replace(['public/', 'storage/'], '', $user->avatar), '/');
                    if (Storage::disk('public')->exists($oldCleanPath)) {
                        Storage::disk('public')->delete($oldCleanPath);
                    }
                }

                $user->avatar = $path;
            }

            $user->save();
        }

        return back()->with('success', 'Profil dan foto profil Anda berhasil diperbarui!');
    }
}
