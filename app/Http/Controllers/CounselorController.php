<?php

namespace App\Http\Controllers;

use App\Models\Counselor;
use Illuminate\Http\Request;

class CounselorController extends Controller
{
    /**
     * Display the counselor directory.
     */
    public function index()
    {
        // Fetch counselors with their linked user names/emails to avoid N+1 query issues
        $counselors = Counselor::with('user')->get();

        // Return the view located at resources/views/admin/counsillor_directory.blade.php
        return view('admin.counsillor_directory', compact('counselors'));
    }
    public function create() {
        return view('admin.counselors.create'); // Create a form for name, email, specialization, etc.
    }

    public function store(Request $request) {
        // 1. Validate request
        // 2. Create User record (auth)
        // 3. Create Counselor record (personal details)
        return redirect()->route('counselors.index')->with('success', 'Counselor registered successfully.');
    }
}
