<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function show(Event $event)
    {
        // Only allow viewing if approved OR if the user is the organizer/admin
        if ($event->status !== 'approved') {
            if (!auth()->check() || (auth()->user()->role !== 'admin' && auth()->id() !== $event->organizer_id)) {
                abort(404);
            }
        }

        return view('events.show', compact('event'));
    }

    public function calendar(Event $event)
    {
        if ($event->status !== 'approved') {
            abort(404);
        }

        $start = \Carbon\Carbon::parse($event->date->format('Y-m-d') . ' ' . $event->time->format('H:i:s'), 'UTC');
        $end = (clone $start)->addHours(2);

        $format = 'Ymd\THis\Z';
        
        $summary = str_replace([",", ";"], ["\\,", "\\;"], $event->title);
        $description = str_replace(["\r\n", "\r", "\n"], "\\n", $event->description);
        $description = str_replace([",", ";"], ["\\,", "\\;"], $description);
        $location = str_replace([",", ";"], ["\\,", "\\;"], $event->location);

        $ics = [
            "BEGIN:VCALENDAR",
            "VERSION:2.0",
            "PRODID:-//Evento//Events//EN",
            "BEGIN:VEVENT",
            "UID:" . md5($event->id . $event->created_at),
            "DTSTAMP:" . now()->format($format),
            "DTSTART:" . $start->format($format),
            "DTEND:" . $end->format($format),
            "SUMMARY:" . $summary,
            "DESCRIPTION:" . $description,
            "LOCATION:" . $location,
            "END:VEVENT",
            "END:VCALENDAR"
        ];

        $content = implode("\r\n", $ics);
        $filename = \Illuminate\Support\Str::slug($event->title) . ".ics";

        return response($content)
            ->header('Content-Type', 'text/calendar; charset=utf-8')
            ->header('Content-Disposition', 'attachment; filename="evento-' . $filename . '"');
    }
}
