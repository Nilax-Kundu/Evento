<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-slate-900 leading-tight">
                {{ __('Event Details: ') }} {{ $event->title }}
            </h2>
            <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center px-4 py-2 bg-slate-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-slate-700 transition ease-in-out duration-150">
                Back to Dashboard
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-8">
                    
                    <div class="flex justify-between items-start mb-6 pb-6 border-b border-slate-200">
                        <div>
                            <h1 class="text-2xl font-bold text-slate-900">{{ $event->title }}</h1>
                            <p class="text-sm text-slate-500 mt-1">Organized by <span class="font-medium text-slate-800">{{ $event->organizer->name }}</span> ({{ $event->organizer->email }})</p>
                        </div>
                        <div>
                            @if($event->status === 'approved')
                                <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full bg-green-100 text-green-800">Approved</span>
                            @elseif($event->status === 'pending')
                                <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">Pending Review</span>
                            @else
                                <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full bg-red-100 text-red-800">Rejected</span>
                            @endif
                        </div>
                    </div>

                    <div class="mb-8">
                        <h3 class="text-lg font-medium text-slate-900 mb-2">Description</h3>
                        <div class="prose max-w-none text-slate-600 bg-slate-50 p-4 rounded-md">
                            {!! nl2br(e($event->description)) !!}
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
                        <div>
                            <h3 class="text-lg font-medium text-slate-900 mb-3">Event Schedule & Location</h3>
                            <ul class="space-y-3 text-sm text-slate-600">
                                <li><strong>Date:</strong> {{ $event->date->format('l, F j, Y') }}</li>
                                <li><strong>Time:</strong> {{ $event->time->format('g:i A') }}</li>
                                <li><strong>Location:</strong> {{ $event->location }}</li>
                            </ul>
                        </div>
                        
                        <div>
                            <h3 class="text-lg font-medium text-slate-900 mb-3">Registration Details</h3>
                            <ul class="space-y-3 text-sm text-slate-600">
                                <li><strong>Start:</strong> {{ $event->registration_start->format('M d, Y g:i A') }}</li>
                                <li><strong>End:</strong> {{ $event->registration_end->format('M d, Y g:i A') }}</li>
                                <li><strong>Capacity:</strong> {{ $event->max_participants }} participants</li>
                                <li><strong>Fee:</strong> {{ $event->registration_fee > 0 ? '₹' . number_format($event->registration_fee, 2) : 'Free Entry' }}</li>
                            </ul>
                        </div>
                    </div>

                    @if($event->image)
                        <div class="mb-8">
                            <h3 class="text-lg font-medium text-slate-900 mb-2">Event Image</h3>
                            <img src="{{ filter_var($event->image, FILTER_VALIDATE_URL) ? $event->image : asset('storage/' . $event->image) }}" alt="Event image" class="max-w-full h-auto max-h-64 object-cover rounded-md border border-slate-200">
                        </div>
                    @endif

                    @if($event->status === 'pending')
                        <div class="mt-8 pt-6 border-t border-slate-200 flex items-center justify-end space-x-4">
                            <form action="{{ route('admin.events.reject', $event) }}" method="POST">
                                @csrf
                                <button type="submit" class="inline-flex items-center px-4 py-2.5 bg-red-600 border border-transparent rounded-lg font-medium text-sm text-white hover:bg-red-700 transition ease-in-out duration-150">
                                    Reject Event
                                </button>
                            </form>

                            <form action="{{ route('admin.events.approve', $event) }}" method="POST">
                                @csrf
                                <button type="submit" class="inline-flex items-center px-4 py-2.5 bg-green-600 border border-transparent rounded-lg font-medium text-sm text-white hover:bg-green-700 transition ease-in-out duration-150">
                                    Approve Event
                                </button>
                            </form>
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
