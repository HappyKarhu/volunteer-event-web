<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use App\Models\Event;

class BookmarkController extends Controller
{
    //@ description: Get all users bookmarks
    //@route GET/bookmarks
    public function index(): View {
        $user = Auth::user();

        $bookmarks = $user->savedEvents()->orderBy('event_user_saves.created_at', 'desc')->paginate(9);
        return view('events.saved')->with('bookmarks', $bookmarks);
        }


    // @desc   Store a bookmark
    // @route  POST /bookmarks/{job}
    public function store(Event $event): RedirectResponse
    {
        $user = Auth::user();

        // Check if the event is already bookmarked
        if ($user->savedEvents()->where('event_id', $event->id)->exists()) {
            return back()->with('status', 'Event is already bookmarked.');
        }

        // Create a new bookmark
        $user->savedEvents()->attach($event->id);

        return back()->with('success', 'Event bookmarked successfully.');
    }   

    // @desc   Remove a bookmark
    // @route  DELETE /bookmarks/{job}
    public function destroy(Event $event): RedirectResponse
    {
        $user = Auth::user();

        // Check if the event is not bookmarked before trying to remove it, samo ce je bookmarked
        if (!$user->savedEvents()->where('event_id', $event->id)->exists()) {
            return back()->with('error', 'Event is not bookmarked.');
        }

        // Remove the bookmark
        $user->savedEvents()->detach($event->id);

        return back()->with('status', 'Event removed from bookmarks successfully.');
}
}