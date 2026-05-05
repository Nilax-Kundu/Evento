<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\RegistrationConfirmed;
use Illuminate\Support\Str;

class RegistrationController extends Controller
{
    public function index()
    {
        $registrations = auth()->user()->registrations()->with('event')->get();
        
        $upcomingRegistrations = $registrations->filter(function ($reg) {
            return $reg->event->date >= now()->startOfDay();
        })->sortBy('event.date');

        $pastRegistrations = $registrations->filter(function ($reg) {
            return $reg->event->date < now()->startOfDay();
        })->sortByDesc('event.date');

        return view('registrations.index', compact('upcomingRegistrations', 'pastRegistrations'));
    }

    public function store(Request $request, Event $event)
    {
        if ($event->status !== 'approved') {
            abort(404);
        }

        $now = now();

        if ($now < $event->registration_start || $now > $event->registration_end) {
            return back()->with('error', 'Registration is currently closed.');
        }

        if ($event->registrations()->count() >= $event->max_participants) {
            return back()->with('error', 'Event is at full capacity.');
        }

        if ($event->registrations()->where('user_id', auth()->id())->exists()) {
            return back()->with('error', 'You have already registered for this event.');
        }

        $registration = auth()->user()->registrations()->create([
            'event_id'  => $event->id,
            'ticket_id' => Str::uuid(),
        ]);

        Mail::to(auth()->user()->email)->send(new RegistrationConfirmed($registration));

        return redirect()->route('registrations.index')->with('success', 'Registration successful! Here is your ticket.');
    }

    public function destroy(\App\Models\Registration $registration)
    {
        if ($registration->user_id !== auth()->id()) {
            abort(403);
        }

        $event = $registration->event;
        $eventDateTime = \Carbon\Carbon::parse($event->date->format('Y-m-d') . ' ' . $event->time->format('H:i:s'));

        if (now()->greaterThanOrEqualTo($eventDateTime)) {
            return back()->with('error', 'Cannot cancel registration after the event has started.');
        }

        $registration->delete();

        return back()->with('success', 'Registration cancelled successfully.');
    }
}
