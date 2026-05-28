<?php

namespace App\Http\Controllers;

use App\Models\Conversation;
use Illuminate\Http\Request;

class AnonymousController extends Controller
{
    /**
     * Display a listing of anonymous conversations and their aliases.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Paginate using the Conversation Model instance
        $conversations = Conversation::orderBy('created_at', 'desc')->paginate(10);

        return view('admin.anonymous_users', compact('conversations'));
    }
}
