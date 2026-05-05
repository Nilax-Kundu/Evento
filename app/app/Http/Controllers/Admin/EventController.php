<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function dashboard()
    {
        $events = Event::with('organizer')->orderBy('created_at', 'desc')->get();
        return view('admin.dashboard', compact('events'));
    }

    public function show(Event $event)
    {
        $event->load('organizer');
        return view('admin.event-show', compact('event'));
    }

    public function approve(Event $event)
    {
        $event->update(['status' => 'approved']);
        return redirect()->route('admin.dashboard')->with('success', 'Event approved successfully.');
    }

    public function reject(Event $event)
    {
        $event->update(['status' => 'rejected']);
        return redirect()->route('admin.dashboard')->with('success', 'Event rejected.');
    }
}
