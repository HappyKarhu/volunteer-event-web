<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Services\FirebaseService;

class UserController extends Controller
{
    protected FirebaseService $firebaseService;

    public function __construct(FirebaseService $firebaseService)
    {
        $this->firebaseService = $firebaseService;
    }

    /**
     * Register a new user
     */
    public function register(Request $request)
    {
        $validated = $request->validate([
            'full_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:8',
            'role' => 'nullable|in:organizer,volunteer',
        ]);

        try {
            // Register with Firebase
            $firebaseUser = $this->firebaseService->registerUser(
                $validated['email'],
                $validated['password'],
                ['role' => $validated['role'] ?? 'volunteer']
            );

            // Create user in database
            $user = User::create([
                'firebase_uid' => $firebaseUser['uid'],
                'full_name' => $validated['full_name'],
                'email' => $validated['email'],
                'role' => $validated['role'] ?? 'volunteer',
            ]);

            return response()->json([
                'message' => 'User registered successfully',
                'user' => $user,
                'firebase_uid' => $firebaseUser['uid'],
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Registration failed: ' . $e->getMessage(),
            ], 400);
        }
    }

    /**
     * Get current logged-in user profile
     */
    public function profile(Request $request)
    {
        try {
            $user = $request->user();

            return response()->json([
                'user' => $user,
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to fetch profile: ' . $e->getMessage(),
            ], 400);
        }
    }

    /**
     * Update user profile
     */
    public function updateProfile(Request $request)
    {
        try {
            $validated = $request->validate([
                'full_name' => 'nullable|string|max:255',
                'avatar' => 'nullable|url',
                'city' => 'nullable|string|max:255',
                'country' => 'nullable|string|max:255',
            ]);

            $user = $request->user();
            $user->update($validated);

            return response()->json([
                'message' => 'Profile updated successfully',
                'user' => $user,
            ], 200);

        } catch (\Exception $e) {
            
            return response()->json([
                'error' => 'Update failed: ' . $e->getMessage(),
            ], 400);
        }
    }
}