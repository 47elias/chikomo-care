<?php

namespace App\Http\Controllers;

use App\Models\PeerStory;
use Illuminate\Http\Request;

class PeerStoryController extends Controller
{
    /**
     * Display a comprehensive ledger of user-submitted peer logs.
     */
    public function index()
    {
        $stories = PeerStory::orderBy('created_at', 'desc')->get();
        return view('admin.peer_stories.index', compact('stories'));
    }

    /**
     * Toggle the formal visibility validation check for front-end presentation display.
     */
    public function toggleApproval($id)
    {
        $story = PeerStory::findOrFail($id);
        $story->is_approved = !$story->is_approved;
        $story->save();

        $statusMessage = $story->is_approved
            ? 'Peer historical timeline log approved for public application views.'
            : 'Peer narrative visibility state set to hidden.';

        return redirect()->back()->with('success', $statusMessage);
    }

    /**
     * Purge a user-submitted story completely from the active care engine database index.
     */
    public function destroy($id)
    {
        $story = PeerStory::findOrFail($id);
        $story->delete();

        return redirect()->back()->with('success', 'Peer review element cleared successfully.');
    }
}
