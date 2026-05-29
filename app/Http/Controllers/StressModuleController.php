<?php

namespace App\Http\Controllers;

use App\Models\StressModule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

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
            // Stores inside storage/app/public/stress_modules/
            $path = $request->file('pdf_file')->store('stress_modules', 'public');

            StressModule::create([
                'title' => $request->title,
                'description' => $request->description,
                'file_path' => $path,
            ]);

            return redirect()->back()->with('success', 'Stress module PDF compiled and uploaded successfully.');
        }

        return redirect()->back()->with('error', 'File processing failed. Please verify file format.');
    }

    public function destroy($id)
    {
        $module = StressModule::findOrFail($id);

        // Delete disk storage link track before removing metadata index record
        if (Storage::disk('public')->exists($module->file_path)) {
            Storage::disk('public')->delete($module->file_path);
        }

        $module->delete();
        return redirect()->back()->with('success', 'Stress module purged successfully.');
    }
}
