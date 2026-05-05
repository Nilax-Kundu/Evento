<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-slate-900 leading-tight">
                {{ __('Your Events') }}
            </h2>
            <a href="{{ route('organizer.events.create') }}" class="inline-flex items-center px-4 py-2.5 bg-teal-700 border border-transparent rounded-lg font-medium text-sm text-white hover:bg-teal-600 transition ease-in-out duration-150">
                Create New Event
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-xl relative" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            <!-- Stats Overview -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="bg-white p-6 rounded-xl border border-slate-200 shadow-sm">
                    <p class="text-sm font-medium text-slate-500 uppercase tracking-wider mb-1">Total Events</p>
                    <p class="text-3xl font-bold text-slate-900">{{ $stats['total_events'] }}</p>
                </div>
                <div class="bg-white p-6 rounded-xl border border-slate-200 shadow-sm">
                    <p class="text-sm font-medium text-slate-500 uppercase tracking-wider mb-1">Total Registrations</p>
                    <p class="text-3xl font-bold text-slate-900">{{ $stats['total_registrations'] }}</p>
                </div>
                <div class="bg-white p-6 rounded-xl border border-slate-200 shadow-sm">
                    <p class="text-sm font-medium text-slate-500 uppercase tracking-wider mb-1">Active Events</p>
                    <p class="text-3xl font-bold text-slate-900">{{ $stats['active_events'] }}</p>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-slate-200">
                <div class="p-6 text-slate-900">
                    @if($events->isEmpty())
                        <div class="text-center py-8">
                            <svg class="mx-auto h-12 w-12 text-slate-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                            <h3 class="text-lg font-medium text-slate-900">No events yet</h3>
                            <p class="mt-1 text-sm text-slate-500">Get started by creating your first event.</p>
                            <div class="mt-6">
                                <a href="{{ route('organizer.events.create') }}" class="inline-flex items-center px-4 py-2.5 border border-transparent shadow-sm text-sm font-medium rounded-lg text-white bg-teal-700 hover:bg-teal-600 transition ease-in-out duration-150">
                                    <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                    Create Event
                                </a>
                            </div>
                        </div>
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-slate-200">
                                <thead class="bg-slate-50">
                                    <tr>
                                        <th scope="col" class="px-4 py-2 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Event Name</th>
                                        <th scope="col" class="px-4 py-2 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Date & Time</th>
                                        <th scope="col" class="px-4 py-2 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Status</th>
                                        <th scope="col" class="px-4 py-2 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Registrations</th>
                                        <th scope="col" class="px-4 py-2 text-right text-xs font-medium text-slate-500 uppercase tracking-wider">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-slate-200">
                                    @foreach($events as $event)
                                        <tr class="hover:bg-slate-50">
                                            <td class="px-4 py-2 whitespace-nowrap">
                                                <div class="text-sm font-medium text-slate-900">{{ $event->title }}</div>
                                            </td>
                                            <td class="px-4 py-2 whitespace-nowrap">
                                                <div class="text-sm text-slate-900">{{ $event->date->format('M d, Y') }}</div>
                                                <div class="text-sm text-slate-500">{{ $event->time->format('h:i A') }}</div>
                                            </td>
                                            <td class="px-4 py-2 whitespace-nowrap">
                                                @if($event->status === 'approved')
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Approved</span>
                                                @elseif($event->status === 'pending')
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">Pending</span>
                                                @else
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">Rejected</span>
                                                @endif
                                            </td>
                                            <td class="px-4 py-2 whitespace-nowrap">
                                                @php
                                                    $count = $event->registrations_count;
                                                    $max = $event->max_participants;
                                                    $percent = $max > 0 ? min(100, ($count / $max) * 100) : 0;
                                                @endphp
                                                <div class="flex items-center space-x-3">
                                                    <div class="flex-grow bg-slate-100 rounded-full h-2 max-w-[100px]">
                                                        <div class="bg-teal-700 h-2 rounded-full transition-all duration-500" style="width: {{ $percent }}%"></div>
                                                    </div>
                                                    <span class="text-sm text-slate-600 font-medium">{{ $count }} / {{ $max }}</span>
                                                </div>
                                            </td>
                                            <td class="px-4 py-2 whitespace-nowrap text-right text-sm font-medium">
                                                <a href="{{ route('events.show', $event) }}" class="text-teal-700 hover:text-teal-900 mr-3">View</a>
                                                <a href="{{ route('organizer.events.edit', $event) }}" class="text-yellow-600 hover:text-yellow-900 mr-3">Edit</a>
                                                <a href="{{ route('organizer.events.attendees', $event) }}" class="text-teal-700 hover:text-teal-900">Attendees</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
