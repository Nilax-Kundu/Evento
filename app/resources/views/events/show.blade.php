<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-slate-900 leading-tight">
            {{ $event->title }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <div class="mb-6">
                <a href="{{ route('home') }}" class="inline-flex items-center text-sm font-medium text-slate-500 hover:text-teal-700 transition duration-150">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    Back to Events
                </a>
            </div>
            
            @if(session('success'))
                <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            @if(session('error'))
                <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">{{ session('error') }}</span>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                @if($event->image)
                    <img src="{{ filter_var($event->image, FILTER_VALIDATE_URL) ? $event->image : asset('storage/' . $event->image) }}" alt="{{ $event->title }}" class="w-full h-64 object-cover">
                @else
                    <div class="w-full h-64 bg-slate-50 flex items-center justify-center">
                        <span class="text-slate-200 text-5xl font-bold">Event Image</span>
                    </div>
                @endif
                
                <div class="p-8">
                    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 border-b border-slate-200 pb-6">
                        <div>
                            <h1 class="text-3xl font-bold text-slate-900">{{ $event->title }}</h1>
                            <p class="text-sm text-slate-500 mt-1">Organized by {{ $event->organizer->name }}</p>
                        </div>
                        
                        <div class="mt-4 md:mt-0 flex flex-col items-end">
                            @if($event->registrations()->count() >= $event->max_participants)
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold bg-red-100 text-red-800">
                                    Event Full
                                </span>
                            @elseif($event->registration_status === 'open')
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold bg-teal-100 text-teal-800">
                                    Open
                                </span>
                            @elseif($event->registration_status === 'upcoming')
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold bg-slate-100 text-slate-700">
                                    Upcoming
                                </span>
                            @else
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold bg-gray-100 text-gray-600">
                                    Closed
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                        <!-- Main Content -->
                        <div class="md:col-span-2">
                            <h3 class="text-lg font-semibold text-slate-900 mb-3">About this event</h3>
                            <div class="prose max-w-none text-slate-600">
                                {!! nl2br(e($event->description)) !!}
                            </div>
                        </div>

                        <!-- Sidebar Info -->
                        <div class="bg-slate-50 p-6 rounded-lg border border-slate-200 self-start">
                            <h3 class="text-lg font-semibold text-slate-900 mb-4">Event Details</h3>
                            
                            <ul class="space-y-4">
                                <li class="flex items-start">
                                    <svg class="w-5 h-5 mr-3 text-teal-700 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                    <div>
                                        <span class="block text-sm font-medium text-slate-900">Date & Time</span>
                                        <span class="block text-sm text-slate-500">{{ $event->date->format('l, F j, Y') }}</span>
                                        <span class="block text-sm text-slate-500">{{ $event->time->format('g:i A') }}</span>
                                    </div>
                                </li>
                                
                                <li class="flex items-start">
                                    <svg class="w-5 h-5 mr-3 text-teal-700 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                    <div>
                                        <span class="block text-sm font-medium text-slate-900">Location</span>
                                        <span class="block text-sm text-slate-500">{{ $event->location }}</span>
                                    </div>
                                </li>
                                
                                <li class="flex items-start">
                                    <svg class="w-5 h-5 mr-3 text-teal-700 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"></path></svg>
                                    <div>
                                        <span class="block text-sm font-medium text-slate-900">Fee</span>
                                        <span class="block text-sm text-slate-500">{{ $event->registration_fee ? '$'.number_format($event->registration_fee, 2) : 'Free' }}</span>
                                    </div>
                                </li>
                                
                                <li class="flex items-start">
                                    <svg class="w-5 h-5 mr-3 text-slate-400 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                                    <div>
                                        <span class="block text-sm font-medium text-slate-900">Capacity</span>
                                        <span class="block text-sm text-slate-500">{{ max(0, $event->max_participants - $event->registrations()->count()) }} seats left</span>
                                    </div>
                                </li>
                            </ul>

                            <div class="mt-8 pt-6 border-t border-gray-200">
                                @if($event->registrations()->count() >= $event->max_participants)
                                    <div class="w-full text-center py-2.5 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-gray-400 cursor-not-allowed opacity-50">
                                        Event Full
                                    </div>
                                @elseif($event->registration_status === 'upcoming')
                                    <div class="w-full text-center py-2.5 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-gray-400 cursor-not-allowed opacity-50">
                                        Opens {{ $event->registration_start->format('M d, Y') }}
                                    </div>
                                @elseif($event->registration_status === 'closed')
                                    <div class="w-full text-center py-2.5 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-gray-400 cursor-not-allowed opacity-50">
                                        Registration Closed
                                    </div>
                                @else
                                    @auth
                                        @if(auth()->user()->role === 'user')
                                            @if($event->registrations()->where('user_id', auth()->id())->exists())
                                                <div class="w-full text-center py-2.5 border border-green-200 rounded-lg text-sm font-medium bg-green-50 text-green-700 cursor-default">
                                                    ✓ You are registered
                                                </div>
                                            @else
                                                <form action="{{ route('registrations.store', $event) }}" method="POST">
                                                    @csrf
                                                    <button type="submit" class="w-full flex justify-center py-2.5 px-4 rounded-lg shadow-sm text-sm font-medium text-white bg-teal-700 hover:bg-teal-600 transition duration-150">
                                                        Register Now
                                                    </button>
                                                </form>
                                            @endif
                                        @else
                                            <div class="text-center p-3 bg-slate-100 text-slate-600 rounded-md text-sm">
                                                Only standard users can register for events.
                                            </div>
                                        @endif
                                    @else
                                    @endauth
                                @endif
                            </div>

                            @if($event->status === 'approved')
                                <div class="mt-4 space-y-2">
                                    <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">Save to Calendar</p>
                                    <div class="grid grid-cols-2 gap-2">
                                        @php
                                            $start = \Carbon\Carbon::parse($event->date->format('Y-m-d') . ' ' . $event->time->format('H:i:s'), 'UTC');
                                            $end = (clone $start)->addHours(2);
                                            $googleDates = $start->format('Ymd\THis\Z') . '/' . $end->format('Ymd\THis\Z');
                                            
                                            $googleUrl = "https://www.google.com/calendar/render?action=TEMPLATE" .
                                                "&text=" . urlencode($event->title) .
                                                "&dates=" . $googleDates .
                                                "&details=" . urlencode($event->description) .
                                                "&location=" . urlencode($event->location);
                                        @endphp
                                        <a href="{{ $googleUrl }}" target="_blank" class="inline-flex justify-center items-center px-3 py-2 border border-slate-200 rounded-lg text-xs font-medium text-slate-700 bg-white hover:bg-slate-50 transition duration-150">
                                            <svg class="w-4 h-4 mr-1.5 text-slate-400" fill="currentColor" viewBox="0 0 24 24"><path d="M21.35 11.1h-9.17v2.73h6.51c-.33 1.56-1.56 2.73-3.21 2.73a4.09 4.09 0 0 1-4.09-4.09c0-2.26 1.83-4.09 4.09-4.09 1.1 0 2.01.38 2.72 1.02l2.12-2.12C19.1 6.13 17.14 5 14.73 5c-4.97 0-9 4.03-9 9s4.03 9 9 9c4.41 0 8.08-3.31 8.85-7.65.1-.65.1-.9.1-1.25z"/></svg>
                                            Google
                                        </a>
                                        <a href="{{ route('events.calendar', $event) }}" class="inline-flex justify-center items-center px-3 py-2 border border-slate-200 rounded-lg text-xs font-medium text-slate-700 bg-white hover:bg-slate-50 transition duration-150">
                                            <svg class="w-4 h-4 mr-1.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                                            .iCal
                                        </a>
                                    </div>
                                    <div class="mt-2" x-data="{ copied: false }">
                                        <button 
                                            @click="navigator.clipboard.writeText(window.location.href); copied = true; setTimeout(() => copied = false, 2000)"
                                            class="w-full inline-flex justify-center items-center px-3 py-2 border border-slate-200 rounded-lg text-xs font-medium text-slate-700 bg-white hover:bg-slate-50 transition duration-150"
                                        >
                                            <svg x-show="!copied" class="w-4 h-4 mr-1.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3"></path></svg>
                                            <svg x-show="copied" class="w-4 h-4 mr-1.5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" x-cloak><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                            <span x-text="copied ? 'Copied!' : 'Copy Event Link'"></span>
                                        </button>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
