<?php

namespace App\Http\Controllers;

use App\Models\ChatSession;
use Illuminate\View\View;

class ChatSessionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $sessions = auth()->user()->chatSessions()->withCount('chatMessages')->latest()->get();

        return view('sessions.index', compact('sessions'));
    }

    /**
     * Display the specified resource.
     */
    public function show(ChatSession $chatSession): View
    {
        abort_unless($chatSession->user_id === auth()->id(), 403);

        // N+1 DEMO: remove before production
        $naive = request()->boolean('naive');
        if (! $naive) {
            $chatSession->load('chatMessages.toolExecutionLogs');
        }

        return view('sessions.show', compact('chatSession', 'naive'));
    }
}
