<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use Illuminate\Support\Str;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'full_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.Teacher::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        // Create teacher record
        $teacher = Teacher::create([
            'id' => Str::uuid(),
            'full_name' => $request->full_name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'is_active' => true,
        ]);

        // Create corresponding profile in profiles table
        $profile = User::create([
            'id' => Str::uuid(),
            'email' => $request->email,
            'full_name' => $request->full_name,
            'role' => 'teacher',
        ]);

        // Link teacher to profile
        $teacher->update(['user_id' => $profile->id]);

        event(new Registered($teacher));
        Auth::login($teacher);

        return redirect(route('dashboard', absolute: false));
    }
}