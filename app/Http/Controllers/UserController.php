<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::all();
        return view('admin.users', compact('users'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|string|email|max:255|unique:users',
            'role'     => 'required|string|in:admin,counselor',
            'password' => 'required|string|min:6',
            'status'   => 'required|boolean',
        ]);

        User::create([
            'name'     => $validated['name'],
            'email'    => $validated['email'],
            'role'     => $validated['role'],
            'password' => Hash::make($validated['password']),
            'status'   => $validated['status'],
        ]);

        return redirect()->route('admin.users.index')
            ->with('success', 'User account initialized successfully.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'role'     => 'required|string|in:admin,counselor',
            'password' => 'nullable|string|min:6',
            'status'   => 'required|boolean',
        ]);

        // Guard: Prevent self-deactivation loops
        if ($user->id === auth()->id() && $validated['status'] == false) {
            return redirect()->route('admin.users.index')
                ->withErrors('Action aborted. You cannot drop your own profile into a deactivated state.');
        }

        $updateData = [
            'name'   => $validated['name'],
            'email'  => $validated['email'],
            'role'   => $validated['role'],
            'status' => $validated['status'],
        ];

        if (!empty($validated['password'])) {
            $updateData['password'] = Hash::make($validated['password']);
        }

        $user->update($updateData);

        return redirect()->route('admin.users.index')
            ->with('success', 'User parameters modified successfully.');
    }

    /**
     * Inline Toggle State Action Handler
     */
    public function toggleStatus($id)
    {
        $user = User::findOrFail($id);

        if ($user->id === auth()->id()) {
            return redirect()->route('admin.users.index')
                ->withErrors('Action Denied. Deactivating your current operational login is restricted.');
        }

        // Toggle state context
        $user->status = !$user->status;
        $user->save();

        $statusText = $user->status ? 'activated' : 'deactivated';
        return redirect()->route('admin.users.index')
            ->with('success', "User account successfully {$statusText}.");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);

        if ($user->id === auth()->id()) {
            return redirect()->route('admin.users.index')
                ->withErrors('Destruction aborted. Self deletion actions are locked.');
        }

        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', 'User account completely erased from data layers.');
    }
}
