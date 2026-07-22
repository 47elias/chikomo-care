<?php

namespace App\Http\Controllers;

use App\Models\StressModule;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class StressModuleController extends Controller
{
    public function index()
    {
        $modules = StressModule::orderBy('created_at', 'desc')->get();
        return view('admin.stress_modules.index', compact('modules'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'pdf_file' => 'required|mimes:pdf|max:10240', // Max limit: 10MB
        ]);

        if ($request->hasFile('pdf_file')) {
            $file = $request->file('pdf_file');

            // Generate a safe, unique filename to avoid collisions/overwrites
            $filename = time() . '_' . Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $file->getClientOriginalExtension();

            // Target folder lives directly inside /public — no storage:link needed
            $destinationPath = public_path('uploads/stress_modules');

            // Ensure the folder exists (safe to call even if it already does)
            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0755, true);
            }

            $file->move($destinationPath, $filename);

            // Store only the relative path from /public, e.g. "uploads/stress_modules/xxx.pdf"
            StressModule::create([
                'title' => $request->title,
                'description' => $request->description,
                'file_path' => 'uploads/stress_modules/' . $filename,
            ]);

            return redirect()->back()->with('success', 'Stress module PDF compiled and uploaded successfully.');
        }

        return redirect()->back()->with('error', 'File processing failed. Please verify file format.');
    }

    public function destroy($id)
    {
        $module = StressModule::findOrFail($id);

        // Delete the physical file directly from /public — no disk facade needed
        $fullPath = public_path($module->file_path);

        if (file_exists($fullPath)) {
            unlink($fullPath);
        }

        $module->delete();
        return redirect()->back()->with('success', 'Stress module purged successfully.');
    }
}
