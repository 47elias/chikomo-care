<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Counselor;
use App\Models\CounselorAssignment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class CounselorController extends Controller
{
    /**
     * Display the counselor directory with search and status filtering.
     */
    public function index(Request $request)
    {
        $query = Counselor::with('user');

        // Optional Search filter for names or specializations
        if ($request->has('search')) {
            $query->whereHas('user', function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%');
            })->orWhere('specialization', 'like', '%' . $request->search . '%');
        }

        $counselors = $query->latest()->get();

        return view('admin.counsillor_directory', compact('counselors'));
    }

    /**
     * View detailed assignment logs for oversight.
     */
    public function assignmentLogs()
    {
        // Paginating 15 logs per page to maintain high dashboard performance
        $assignments = CounselorAssignment::with(['counselor.user', 'conversation'])
                        ->latest()
                        ->paginate(15);

        return view('admin.counsillor_log', compact('assignments'));
    }

    /**
     * Store a new Counselor and their associated User account.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'specialization' => 'required|string',
            'license_number' => 'required|string|unique:counselors,license_number',
            'experience_years' => 'required|integer|min:0',
            'bio' => 'nullable|string',
        ]);

        try {
            DB::transaction(function () use ($request) {
                // Create the Auth User record
                $user = User::create([
                    'name' => $request->name,
                    'email' => $request->email,
                    'password' => Hash::make('ChikomoCare2026'), // Default system password
                    'role' => 'counselor',
                ]);

                // Create the Counselor profile linked to the User
                $user->counselor()->create([
                    'specialization' => $request->specialization,
                    'license_number' => $request->license_number,
                    'experience_years' => $request->experience_years,
                    'bio' => $request->bio,
                    'status' => 'available',
                ]);
            });

            return redirect()->route('counselors.index')->with('success', 'Counselor registered successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Registration failed: ' . $e->getMessage());
        }
    }

    /**
     * Update the Counselor's professional profile.
     */
    public function update(Request $request, $id)
    {
        $counselor = Counselor::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('users')->ignore($counselor->user_id)],
            'specialization' => 'required|string',
            'status' => 'required|in:available,busy,on_leave',
            'experience_years' => 'required|integer|min:0',
        ]);

        try {
            DB::transaction(function () use ($request, $counselor) {
                // Update User details
                $counselor->user->update([
                    'name' => $request->name,
                    'email' => $request->email,
                ]);

                // Update Counselor professional profile
                $counselor->update($request->only([
                    'specialization', 'status', 'experience_years', 'bio'
                ]));
            });

            return redirect()->back()->with('success', 'Profile updated successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Update failed: ' . $e->getMessage());
        }
    }

    /**
     * Toggle status (Available/Busy) for quick management.
     */
    public function toggleStatus($id)
    {
        $counselor = Counselor::findOrFail($id);
        $newStatus = $counselor->status === 'available' ? 'busy' : 'available';

        $counselor->update(['status' => $newStatus]);

        return redirect()->back()->with('success', "Counselor is now marked as {$newStatus}.");
    }

    /**
     * Remove the counselor and user record (Soft Delete recommended).
     */
    public function destroy($id)
    {
        try {
            $counselor = Counselor::findOrFail($id);

            DB::transaction(function () use ($counselor) {
                // Delete the User (which should cascade to the Counselor profile if defined in migration)
                $counselor->user->delete();
                $counselor->delete();
            });

            return redirect()->back()->with('success', 'Counselor record removed from system.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Deletion failed.');
        }
    }
}
