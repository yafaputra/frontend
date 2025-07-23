<?php

namespace App\Http\Controllers;

use App\Models\UserProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;

class UserProfileController extends Controller
{
    public function show()
    {
        $user = Auth::user();
        // Memuat profil pengguna jika ada, jika tidak, buat objek kosong
        $profile = $user->profile ?? new UserProfile();

        // Pastikan fullname dan email di profil terisi dari user jika kosong
        if (empty($profile->fullname)) {
            $profile->fullname = $user->name;
        }
        if (empty($profile->email)) {
            $profile->email = $user->email;
        }

        return response()->json([
            'user' => $user, // Mengirim data user juga
            'profile' => $profile,
        ]);
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        // Temukan profil atau buat yang baru jika belum ada
        $profile = $user->profile ?? new UserProfile(['user_id' => $user->id]);

        $validator = Validator::make($request->all(), [
            'fullname' => 'required|string|max:255',
            'username' => [
                'nullable',
                'string',
                'max:255',
                // Unik kecuali untuk username profil ini sendiri
                Rule::unique('users_profile')->ignore($profile->id),
            ],
            'dob' => 'nullable|date',
            'email' => [
                'required',
                'email',
                'max:255',
                // Unik kecuali untuk email profil ini sendiri
                Rule::unique('users_profile')->ignore($profile->id),
            ],
            'bio' => 'nullable|string',
            'hobbies' => 'nullable|array',
            'hobbies.*' => 'string|max:255', // Validasi setiap item dalam array
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048', // Max 2MB
            'level' => 'nullable|integer',
            'progress' => 'nullable|integer|between:0,100',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors(), 'message' => 'Validasi gagal.'], 422);
        }

        // Handle avatar upload
        if ($request->hasFile('avatar')) {
            // Hapus avatar lama jika ada
            if ($profile->avatar && Storage::disk('public')->exists($profile->avatar)) {
                Storage::disk('public')->delete($profile->avatar);
            }
            $avatarPath = $request->file('avatar')->store('avatars', 'public');
            $profile->avatar = $avatarPath;
        }

        // Update data profil
        $profile->fullname = $request->fullname;
        $profile->username = $request->username;
        $profile->dob = $request->dob;
        $profile->email = $request->email;
        $profile->bio = $request->bio;
        $profile->hobbies = $request->input('hobbies', []); // Simpan sebagai JSON
        $profile->level = $request->input('level', $profile->level);
        $profile->progress = $request->input('progress', $profile->progress);

        // Pastikan user_id terisi jika ini adalah profil baru
        if (!$profile->exists) {
            $profile->user_id = $user->id;
        }

        $profile->save();

        // Update name dan email di tabel users utama juga jika berbeda
        if ($user->name !== $request->fullname) {
            $user->name = $request->fullname;
        }
        if ($user->email !== $request->email) {
            $user->email = $request->email;
        }
        $user->save(); // Simpan perubahan ke tabel users

        return response()->json([
            'message' => 'Profil berhasil diperbarui!',
            'profile' => $profile,
            'avatar_url' => $profile->avatar ? Storage::url($profile->avatar) : null,
        ]);
    }

    /**
     * Ganti password user yang sedang login
     */
    public function changePassword(Request $request)
    {
        $user = Auth::user();

        $validator = Validator::make($request->all(), [
            'current_password' => 'required',
            'new_password' => 'required|string|min:8|confirmed',
        ], [
            'new_password.confirmed' => 'Konfirmasi password baru tidak cocok.'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors(), 'message' => 'Validasi gagal.'], 422);
        }

        // Cek password lama
        if (!Hash::check($request->current_password, $user->password)) {
            return response()->json(['message' => 'Password lama salah.'], 403);
        }

        // Update password
        $user->password = Hash::make($request->new_password);
        $user->save();

        return response()->json(['message' => 'Password berhasil diubah.']);
    }
}
