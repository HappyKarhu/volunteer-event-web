<?php

namespace App\Http\Controllers;

use App\Models\EventApplication;
use Illuminate\Http\Request;

class EventApplicationMessageController extends Controller
{
    public function store(Request $request, EventApplication $application)
    {
        $user = $request->user();

        abort_unless(
            $user->id === $application->user_id ||
            $user->id === $application->event->organizer_id,
            403
        );

        $validated = $request->validate([
            'message' => 'required|string|max:2000',
        ]);

        $receiverId = $user->id === $application->user_id
            ? $application->event->organizer_id
            : $application->user_id;

        $application->messages()->create([
            'sender_id' => $user->id,
            'receiver_id' => $receiverId,
            'message' => $validated['message'],
        ]);

        return back()->with('success', 'Message sent.');
    }
}