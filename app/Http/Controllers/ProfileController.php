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
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
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

    /**
     * Update profile via API for dashboard
     */
    public function updateProfile(Request $request)
    {
        try {
            \Log::info('Profile update request data:', $request->all());
            \Log::info('Current user ID:', [auth()->id()]);
            \Log::info('Current user data:', [auth()->user()->toArray()]);
            
            // Custom validation for email uniqueness
            $teacher = auth()->user();
            $rules = [
                'full_name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255',
            ];
            
            // Check email uniqueness in both teachers and profiles tables
            $emailExists = false;
            
            // Check in teachers table (exclude current teacher)
            if (\App\Models\Teacher::where('email', $request->email)->where('id', '!=', $teacher->id)->exists()) {
                $emailExists = true;
            }
            
            // Check in profiles table (exclude current teacher's profile if exists)
            if ($teacher->user_id) {
                if (\App\Models\User::where('email', $request->email)->where('id', '!=', $teacher->user_id)->exists()) {
                    $emailExists = true;
                }
            }
            
            if ($emailExists) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation error',
                    'errors' => ['email' => ['Email sudah digunakan oleh user lain.']]
                ], 422);
            }
            
            $request->validate($rules);

            $teacher = auth()->user(); // This is Teacher model
            
            // Update teacher table
            $teacher->full_name = $request->full_name;
            $teacher->email = $request->email;
            $teacher->save();
            
            // Also update the linked profile if exists
            if ($teacher->user_id && $teacher->profile) {
                $profile = $teacher->profile;
                $profile->full_name = $request->full_name;
                $profile->email = $request->email;
                $profile->save();
                \Log::info('Updated linked profile:', $profile->toArray());
            }
            
            \Log::info('Updated teacher data:', $teacher->fresh()->toArray());

            return response()->json([
                'success' => true,
                'message' => 'Profil berhasil diupdate',
                'user' => [
                    'name' => $teacher->full_name,
                    'email' => $teacher->email,
                ]
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengupdate profil: ' . $e->getMessage()
            ], 500);
        }
    }
}
