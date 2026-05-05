<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-slate-900 leading-tight">
            {{ __('Your Tickets') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            <div x-data="{ tab: 'upcoming' }" class="space-y-6">
                <!-- Tabs -->
                <div class="flex border-b border-slate-200">
                    <button @click="tab = 'upcoming'" :class="tab === 'upcoming' ? 'border-teal-700 text-teal-700' : 'border-transparent text-slate-500 hover:text-slate-700'" class="px-6 py-3 border-b-2 font-medium text-sm transition duration-150">
                        Upcoming Events ({{ $upcomingRegistrations->count() }})
                    </button>
                    <button @click="tab = 'past'" :class="tab === 'past' ? 'border-teal-700 text-teal-700' : 'border-transparent text-slate-500 hover:text-slate-700'" class="px-6 py-3 border-b-2 font-medium text-sm transition duration-150">
                        Past Events ({{ $pastRegistrations->count() }})
                    </button>
                </div>

                <!-- Upcoming Tab -->
                <div x-show="tab === 'upcoming'" class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        @if($upcomingRegistrations->isEmpty())
                            <div class="text-center py-12">
                                <svg class="mx-auto h-12 w-12 text-slate-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"></path></svg>
                                <h3 class="text-lg font-medium text-slate-900">No upcoming events</h3>
                                <p class="mt-1 text-sm text-slate-500">You haven't registered for any future events.</p>
                                <div class="mt-6">
                                    <a href="{{ route('home') }}" class="inline-flex items-center px-4 py-2.5 border border-transparent shadow-sm text-sm font-medium rounded-lg text-white bg-teal-700 hover:bg-teal-600 transition duration-150">
                                        Browse Events
                                    </a>
                                </div>
                            </div>
                        @else
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                @foreach($upcomingRegistrations as $registration)
                                    @include('registrations.partials.ticket-card', ['registration' => $registration])
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Past Tab -->
                <div x-show="tab === 'past'" class="bg-white overflow-hidden shadow-sm sm:rounded-lg" x-cloak>
                    <div class="p-6 text-slate-900">
                        @if($pastRegistrations->isEmpty())
                            <div class="text-center py-12">
                                <svg class="mx-auto h-12 w-12 text-slate-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                <h3 class="text-lg font-medium text-slate-900">No past events</h3>
                                <p class="mt-1 text-sm text-slate-500">You haven't attended any events yet.</p>
                            </div>
                        @else
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 opacity-75">
                                @foreach($pastRegistrations as $registration)
                                    @include('registrations.partials.ticket-card', ['registration' => $registration, 'isPast' => true])
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
