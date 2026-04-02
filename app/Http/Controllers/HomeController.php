<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\User;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View {
        $events = Event::where('status', 'published')
                        ->orderBy('start_date', 'asc')
                        ->take(6)
                        ->get();

        return view('pages.index', compact('events'));
    }
}
