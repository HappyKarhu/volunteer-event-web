<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

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
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role' => ['required', 'in:organizer,volunteer'],
            'photo' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,webp','max:2048'], // Optional photo upload
        ]);

        $userData = [
        'name' => $request->name,
        'email' => $request->email,
        'password' => $request->password,
        'role' => $request->role,
    ];

    if ($request->hasFile('photo')) {
        $folder = $request->role === 'organizer' ? 'logos' : 'avatars';
        $photoPath = $request->file('photo')->store($folder, 'public');

        if ($request->role === 'organizer') {
            $userData['logo'] = $photoPath;
        } else {
            $userData['avatar'] = $photoPath;
        }
    }

    $user = User::create($userData);

    event(new Registered($user));
    Auth::login($user);

    return redirect(route('dashboard', absolute: false));
}
}
