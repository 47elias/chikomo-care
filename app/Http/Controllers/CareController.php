<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Api\ChatController;
use App\Http\Controllers\Api\FeaturesController;
use App\Http\Controllers\Api\SessionController;
use App\Models\Conversation;
use App\Models\StressModule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CareController extends Controller
{
    private function conversation(Request $request): Conversation
    {
        $conversation = Conversation::where('token', $request->session()->get('chikomo_token', ''))->first();

        if (! $conversation) {
            $request->headers->remove('X-Chikomo-Token');
            $data = app(SessionController::class)->initialize($request)->getData(true);
            $request->session()->put('chikomo_token', $data['token']);
            $conversation = Conversation::where('token', $data['token'])->firstOrFail();
        }

        return $conversation;
    }

    private function page(Request $request, string $view, array $data = [])
    {
        $conversation = $this->conversation($request);

        return view('care.'.$view, array_merge([
            'conversation' => $conversation,
            'conversations' => Conversation::where('alias', $conversation->alias)->latest('updated_at')->get(),
        ], $data));
    }

    public function chat(Request $request)
    {
        return $this->page($request, 'chat', [
            'messages' => $this->conversation($request)->messages()->orderBy('id')->get(),
        ]);
    }

    public function selectConversation(Request $request, Conversation $conversation)
    {
        abort_unless($conversation->alias === $this->conversation($request)->alias, 404);
        $request->session()->put('chikomo_token', $conversation->token);

        return redirect()->route('care.chat');
    }

    // One-time bridge for visitors whose anonymous identity was stored by React.
    public function restoreSession(Request $request)
    {
        $data = $request->validate(['token' => 'required|string|max:100']);
        $conversation = Conversation::where('token', $data['token'])->firstOrFail();
        $request->session()->regenerate();
        $request->session()->put('chikomo_token', $conversation->token);

        return response()->json(['restored' => true]);
    }

    public function send(Request $request, ChatController $chat)
    {
        $conversation = $this->conversation($request);
        $request->merge(['token' => $conversation->token]);
        $chat->store($request);

        return redirect()->route('care.chat');
    }

    public function counselor(Request $request)
    {
        return $this->page($request, 'counselor');
    }

    public function counselorSync(Request $request)
    {
        $conversation = $this->conversation($request);

        return response()->json([
            'status' => $conversation->status,
            'can_send' => $conversation->is_human_request && $conversation->status === 'active' && (bool) $conversation->counselor_id,
            'html' => view('care.partials.counselor-state', compact('conversation'))->render(),
        ]);
    }

    public function requestCounselor(Request $request, FeaturesController $features)
    {
        $conversation = $this->conversation($request);
        if (! $conversation->is_human_request) {
            $request->headers->set('X-Chikomo-Token', $conversation->token);
            $features->requestCounselor($request);
        }

        return redirect()->route('care.counselor');
    }

    public function sendCounselor(Request $request)
    {
        $data = $request->validate(['message' => 'required|string|max:2000']);
        $conversation = $this->conversation($request);
        DB::transaction(function () use ($conversation, $data) {
            $current = Conversation::whereKey($conversation->id)->lockForUpdate()->firstOrFail();
            abort_unless($current->is_human_request && $current->status === 'active' && $current->counselor_id, 409, 'The counselor session is not active.');
            $current->messages()->create(['content' => $data['message'], 'sender_type' => 'user']);
            $current->touch();
        });

        return redirect()->route('care.counselor');
    }

    public function modules(Request $request)
    {
        return $this->page($request, 'modules', [
            'modules' => StressModule::latest()->get(),
            'comments' => DB::table('stress_module_comments')->orderBy('id')->get()->groupBy('stress_module_id'),
        ]);
    }

    public function download(StressModule $module)
    {
        $root = realpath(public_path('uploads/stress_modules'));
        $path = realpath(public_path($module->file_path));
        abort_unless($root && $path && str_starts_with($path, $root.DIRECTORY_SEPARATOR) && is_file($path), 404);
        $module->increment('download_count');

        return response()->download($path);
    }

    public function comment(Request $request, StressModule $module)
    {
        $data = $request->validate(['comment' => 'required|string|max:2000']);
        DB::table('stress_module_comments')->insert([
            'stress_module_id' => $module->id,
            'author_alias' => $this->conversation($request)->alias,
            'content' => $data['comment'],
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('care.modules')->with('success', 'Feedback posted.');
    }

    public function stories(Request $request)
    {
        return $this->page($request, 'stories', [
            'stories' => DB::table('peer_stories')->where('is_approved', true)->latest()->get(),
        ]);
    }

    public function postStory(Request $request, FeaturesController $features)
    {
        $request->headers->set('X-Chikomo-Token', $this->conversation($request)->token);
        $features->postPeerStory($request);

        return redirect()->route('care.stories')->with('success', 'Story submitted for review! It will appear after approval.');
    }
}
