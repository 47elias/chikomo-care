<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\CounselorAssignment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class CounselorController extends Controller
{
    /**
     * Display the active counselor directory directly from the users table.
     */
    public function index(Request $request)
    {
        // Query the users table directly where the role is 'counselor'
        // If you have a status column on your users table, you can append: ->where('status', 'available')
        $query = User::where('role', 'counselor');

        // Search filter matching name or email inside the users table
        if ($request->has('search') && !empty($request->search)) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }

        $counselors = $query->latest()->get();

        // Dynamically inject fallback profile attributes so the blade template properties don't crash
        $counselors->transform(function ($user) {
            // This maps $counselor->user to itself so the relationship-style view calls ($counselor->user->name) continue to work seamlessly
            $user->user = $user;

            // Provide seamless fallbacks for profile fields if they don't exist as columns yet on your users table
            $user->specialization = $user->specialization ?? 'General Counseling';
            $user->license_number = $user->license_number ?? 'ZIM-MED-' . (1000 + $user->id);
            $user->experience_years = $user->experience_years ?? 2;
            $user->bio = $user->bio ?? 'Professional counselor ready to assist.';
            $user->status = $user->status ?? 'available';

            return $user;
        });

        return view('admin.counsillor_directory', compact('counselors'));
    }

    /**
     * View detailed assignment logs for oversight.
     */
    public function assignmentLogs()
    {
        // Standard logs overview fallback
        $assignments = CounselorAssignment::with(['conversation'])
                        ->latest()
                        ->paginate(15);

        return view('admin.counsillor_log', compact('assignments'));
    }

    /**
     * Store a new Counselor directly into the Users table.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            // Include extra validations if you added these columns directly to your users migration
            'specialization' => 'nullable|string',
            'license_number' => 'nullable|string',
            'experience_years' => 'nullable|integer|min:0',
            'bio' => 'nullable|string',
        ]);

        try {
            User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make('ChikomoCare2026'), // Default system password
                'role' => 'counselor',
                // Optional attributes save safely if the database columns are present
                'specialization' => $request->specialization ?? 'General Counseling',
                'license_number' => $request->license_number ?? 'ZIM-MED-' . rand(1111, 9999),
                'experience_years' => $request->experience_years ?? 1,
                'bio' => $request->bio,
                'status' => 'available',
            ]);

            return redirect()->route('admin.counselors.index')->with('success', 'Counselor registered successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Registration failed: ' . $e->getMessage());
        }
    }

    /**
     * Update the Counselor profile settings directly on the User model.
     */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('users')->ignore($user->id)],
            'specialization' => 'nullable|string',
            'status' => 'required|string',
            'experience_years' => 'nullable|integer|min:0',
            'bio' => 'nullable|string',
        ]);

        try {
            $user->update([
                'name' => $request->name,
                'email' => $request->email,
                'specialization' => $request->specialization,
                'status' => $request->status,
                'experience_years' => $request->experience_years,
                'bio' => $request->bio,
            ]);

            return redirect()->back()->with('success', 'Profile updated successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Update failed: ' . $e->getMessage());
        }
    }

    /**
     * Toggle status parameters directly on the user instance.
     */
    public function toggleStatus($id)
    {
        $user = User::findOrFail($id);

        $currentStatus = $user->status ?? 'available';
        $newStatus = $currentStatus === 'available' ? 'busy' : 'available';

        $user->update(['status' => $newStatus]);

        $message = $newStatus === 'available'
            ? "Counselor is now marked as active."
            : "Counselor is now marked as busy.";

        return redirect()->back()->with('success', $message);
    }

    /**
     * Remove the counselor record from the system users table.
     */
    public function destroy($id)
    {
        try {
            $user = User::findOrFail($id);
            $user->delete();

            return redirect()->back()->with('success', 'Counselor record removed from system.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Deletion failed.');
        }
    }
}
