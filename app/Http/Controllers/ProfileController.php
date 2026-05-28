<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    /**
     * Render the centralized settings dashboard panel.
     */
    public function edit()
    {
        // Points exactly to resources/views/admin/settings.blade.php
        return view('admin.settings');
    }

    /**
     * Coordinate structural profile updates across base and extension tables.
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        // 1. Establish core validation rules for all system accounts
        $rules = [
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('users')->ignore($user->id)],
            'password' => 'nullable|string|min:8|confirmed',
        ];

        // 2. Conditionally append validation constraints if the user is a professional counselor
        if ($user->role === 'counselor' && $user->counselor) {
            $rules['specialization'] = 'required|string';
            $rules['experience_years'] = 'required|integer|min:0';
            $rules['status'] = 'required|in:available,busy,on_leave';
            $rules['bio'] = 'nullable|string';
        }

        $request->validate($rules);

        try {
            // Execute updates inside a secure database transaction block
            DB::transaction(function () use ($request, $user) {

                // Track standard authentication base modifications
                $userData = [
                    'name' => $request->name,
                    'email' => $request->email,
                ];

                // Append new password hash parameters if provided
                if ($request->filled('password')) {
                    $userData['password'] = Hash::make($request->password);
                }

                // Update the users table
                $user->update($userData);

                // If counselor metadata is attached, sync those values into the counselors table
                if ($user->role === 'counselor' && $user->counselor) {
                    $user->counselor->update([
                        'specialization' => $request->specialization,
                        'experience_years' => $request->experience_years,
                        'status' => $request->status,
                        'bio' => $request->bio,
                    ]);
                }
            });

            return redirect()->back()->with('success', 'Your personal account profile settings have been synchronized successfully.');

        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Profile update execution error: ' . $e->getMessage());
        }
    }
}
