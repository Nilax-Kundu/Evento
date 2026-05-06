<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
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

        // Send Ticket Confirmation Email after the response is sent via Resend API
        $userEmail = auth()->user()->email;
        dispatch(function () use ($registration, $userEmail) {
            try {
                Log::info('Attempting to send ticket confirmation to: ' . $userEmail . ' via Resend');

                $response = Http::withHeaders([
                    'Authorization' => 'Bearer ' . env('RESEND_API_KEY'),
                    'Content-Type'  => 'application/json',
                ])->post('https://api.resend.com/emails', [
                    'from' => env('MAIL_FROM', 'onboarding@resend.dev'),
                    'to'   => [env('TEST_EMAIL_RECIPIENT', $userEmail)],
                    'subject' => 'Ticket Confirmation: ' . $registration->event->title,
                    'html' => view('emails.registration-confirmed', ['registration' => $registration])->render(),
                ]);

                if ($response->successful()) {
                    Log::info('Ticket confirmation sent successfully to: ' . $userEmail);
                } else {
                    Log::error('Resend failed for ticket: ' . $response->status() . ' - ' . $response->body());
                }
            } catch (\Exception $e) {
                Log::error('Resend failed for ticket with exception: ' . $e->getMessage());
            }
        })->afterResponse();

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
