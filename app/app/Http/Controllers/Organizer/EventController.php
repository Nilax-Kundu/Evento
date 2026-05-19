<?php

namespace App\Http\Controllers\Organizer;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

use Cloudinary\Cloudinary;

class EventController extends Controller
{
    private function uploadToCloudinary($file)
    {
        $cloudinary = new Cloudinary(env('CLOUDINARY_URL'));
        return $cloudinary->uploadApi()->upload($file->getRealPath())['secure_url'];
    }

    public function dashboard()
    {
        $events = auth()->user()->events()->withCount('registrations')->orderBy('created_at', 'desc')->get();
        
        $stats = [
            'total_events' => $events->count(),
            'total_registrations' => $events->sum('registrations_count'),
            'active_events' => $events->where('status', 'approved')->filter(function($event) {
                return $event->date >= now()->startOfDay();
            })->count(),
        ];

        return view('organizer.dashboard', compact('events', 'stats'));
    }

    public function create()
    {
        return view('organizer.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'              => 'required|string|max:255',
            'description'        => 'required|string|min:20|max:1000',
            'date'               => 'required|date',
            'time'               => 'required',
            'location'           => 'required|string|max:255',
            'max_participants'   => 'required|integer|min:1|max:1000',
            'registration_start' => 'required|date|after_or_equal:now',
            'registration_end'   => 'required|date|after:registration_start',
            'registration_fee'   => 'nullable|numeric|gt:0|max:10000',
            'image'              => 'nullable|image',
        ]);

        $eventDate = \Carbon\Carbon::parse($request->date . ' ' . $request->time);
        $regStart = \Carbon\Carbon::parse($request->registration_start);
        $regEnd = \Carbon\Carbon::parse($request->registration_end);

        if ($regEnd->greaterThanOrEqualTo($eventDate)) {
            return back()->withInput()->withErrors(['registration_end' => 'Registration must end before event starts']);
        }

        if ($regStart->diffInDays($regEnd) > 30) {
            return back()->withInput()->withErrors(['registration_end' => 'Registration period cannot exceed 30 days']);
        }

        $eventData = $validated;
        
        if ($request->hasFile('image')) {
            $eventData['image'] = $this->uploadToCloudinary($request->file('image'));
        }

        $eventData['status'] = 'pending';

        auth()->user()->events()->create($eventData);

        return redirect()->route('organizer.dashboard')->with('success', 'Event created and pending approval.');
    }

    public function edit(Event $event)
    {
        if ($event->organizer_id !== auth()->id()) {
            abort(403);
        }

        return view('organizer.edit', compact('event'));
    }

    public function update(Request $request, Event $event)
    {
        if ($event->organizer_id !== auth()->id()) {
            abort(403);
        }

        $validated = $request->validate([
            'title'              => 'required|string|max:255',
            'description'        => 'required|string|min:20|max:1000',
            'date'               => 'required|date',
            'time'               => 'required',
            'location'           => 'required|string|max:255',
            'max_participants'   => 'required|integer|min:1|max:1000',
            'registration_start' => 'required|date|after_or_equal:now',
            'registration_end'   => 'required|date|after:registration_start',
            'registration_fee'   => 'nullable|numeric|gt:0|max:10000',
            'image'              => 'nullable|image',
        ]);

        $eventDate = \Carbon\Carbon::parse($request->date . ' ' . $request->time);
        $regStart = \Carbon\Carbon::parse($request->registration_start);
        $regEnd = \Carbon\Carbon::parse($request->registration_end);

        if ($regEnd->greaterThanOrEqualTo($eventDate)) {
            return back()->withInput()->withErrors(['registration_end' => 'Registration must end before event starts']);
        }

        if ($regStart->diffInDays($regEnd) > 30) {
            return back()->withInput()->withErrors(['registration_end' => 'Registration period cannot exceed 30 days']);
        }

        $eventData = $validated;
        
        if ($request->hasFile('image')) {
            $eventData['image'] = $this->uploadToCloudinary($request->file('image'));
        } else {
            // Retain old image
            $eventData['image'] = $event->image;
        }

        // Force re-approval
        $eventData['status'] = 'pending';

        $event->update($eventData);

        return redirect()->route('organizer.dashboard')->with('success', 'Event updated and is now pending approval.');
    }

    public function attendees(Event $event)
    {
        if ($event->organizer_id !== auth()->id()) {
            abort(403);
        }

        $registrations = $event->registrations()
            ->with(['user' => function ($query) {
                $query->withTrashed();
            }])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('organizer.attendees', compact('event', 'registrations'));
    }

    public function toggleAttendance(Request $request, \App\Models\Registration $registration)
    {
        if ($registration->event->organizer_id !== auth()->id()) {
            abort(403);
        }

        $registration->update([
            'is_present' => !$registration->is_present,
        ]);

        return back()->with('success', 'Attendance updated.');
    }

    public function export(Event $event)
    {
        if ($event->organizer_id !== auth()->id()) {
            abort(403);
        }

        $registrations = $event->registrations()
            ->with(['user' => function ($query) {
                $query->withTrashed();
            }])
            ->get();

        $filename = "evento-attendees-" . \Illuminate\Support\Str::slug($event->title) . ".csv";
        
        $headers = [
            'Content-Type' => 'text/csv; charset=utf-8',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        $callback = function() use ($registrations) {
            $file = fopen('php://output', 'w');
            
            // Add BOM for Excel UTF-8 support
            fputs($file, "\xEF\xBB\xBF");
            
            // Header
            fputcsv($file, ['Name', 'Email', 'Ticket ID', 'Attendance Status', 'Registration Date']);

            foreach ($registrations as $reg) {
                $userName = $reg->user ? $reg->user->name : 'Deleted User';
                if ($reg->user && $reg->user->trashed()) {
                    $userName .= ' (Deleted User)';
                }
                
                fputcsv($file, [
                    $userName,
                    $reg->user ? $reg->user->email : 'N/A',
                    $reg->ticket_id,
                    $reg->is_present ? 'Present' : 'Absent',
                    $reg->created_at->format('Y-m-d H:i:s'),
                ]);
            }
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
