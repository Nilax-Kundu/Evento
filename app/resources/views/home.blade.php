<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-slate-900 leading-tight">
            {{ __('Explore Events') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <div class="mb-8">
                <form action="{{ route('home') }}" method="GET" class="space-y-3 md:space-y-0 md:flex md:gap-3">
                    <div class="relative flex-grow">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search events by title..." class="block w-full pl-10 pr-3 py-3 border border-slate-200 rounded-xl leading-5 bg-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-teal-500/20 focus:border-teal-500 text-sm transition duration-150 ease-in-out shadow-sm">
                    </div>
                    
                    <div class="flex gap-3">
                        <select name="sort" onchange="this.form.submit()" class="block w-full md:w-48 pl-3 pr-10 py-3 text-sm border-slate-200 focus:outline-none focus:ring-2 focus:ring-teal-500/20 focus:border-teal-500 rounded-xl transition duration-150 ease-in-out shadow-sm bg-white">
                            <option value="asc" {{ request('sort') == 'asc' ? 'selected' : '' }}>Soonest First</option>
                            <option value="desc" {{ request('sort') == 'desc' ? 'selected' : '' }}>Latest First</option>
                        </select>
                        
                        <div class="flex gap-2">
                            <button type="submit" class="inline-flex items-center justify-center px-6 py-3 bg-teal-700 border border-transparent rounded-xl font-semibold text-sm text-white hover:bg-teal-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-teal-500 transition ease-in-out duration-150 shadow-sm whitespace-nowrap">
                                Search
                            </button>
                            @if(request('search') || request('sort'))
                                <a href="{{ route('home') }}" class="inline-flex items-center justify-center px-4 py-3 bg-slate-100 border border-slate-200 rounded-xl font-medium text-sm text-slate-700 hover:bg-slate-200 transition ease-in-out duration-150">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                </a>
                            @endif
                        </div>
                    </div>
                </form>
            </div>
            
            @if(session('error'))
                <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">{{ session('error') }}</span>
                </div>
            @endif

            @if($events->isEmpty())
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-slate-200">
                    <div class="p-6 text-slate-900 text-center py-12">
                        <svg class="mx-auto h-12 w-12 text-slate-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        <h3 class="text-lg font-medium text-slate-900">
                            @if(request('search'))
                                No events found for "{{ request('search') }}"
                            @else
                                No events found
                            @endif
                        </h3>
                        <p class="mt-1 text-sm text-slate-500">Check back later for new events.</p>
                    </div>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($events as $event)
                        <div class="bg-white rounded-lg shadow-sm border border-slate-200 flex flex-col overflow-hidden hover:-translate-y-1 hover:shadow-md transition duration-150">
                            <div class="relative h-48 bg-gray-100">
                                @if($event->image)
                                    <img src="{{ filter_var($event->image, FILTER_VALIDATE_URL) ? $event->image : asset('storage/' . $event->image) }}" alt="{{ $event->title }}" class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full bg-slate-50 flex items-center justify-center">
                                        <svg class="h-16 w-16 text-slate-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                    </div>
                                @endif
                                
                                @if($event->registrations()->count() >= $event->max_participants)
                                    <span class="absolute top-3 right-3 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 shadow-sm">
                                        Event Full
                                    </span>
                                @elseif($event->registration_status === 'open')
                                    <span class="absolute top-3 right-3 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-teal-100 text-teal-800 shadow-sm">
                                        Open
                                    </span>
                                @elseif($event->registration_status === 'upcoming')
                                    <span class="absolute top-3 right-3 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-slate-100 text-slate-700 shadow-sm">
                                        Upcoming
                                    </span>
                                @else
                                    <span class="absolute top-3 right-3 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-600 shadow-sm">
                                        Closed
                                    </span>
                                @endif
                            </div>
                            
                            <div class="p-5 flex-grow space-y-3">
                                <div>
                                    <h3 class="text-lg font-semibold text-slate-900 leading-tight">{{ $event->title }}</h3>
                                </div>
                                
                                <p class="text-sm text-slate-600 line-clamp-2">{{ $event->description }}</p>
                                
                                <div class="space-y-2 text-sm text-slate-500 mt-3">
                                    <div class="flex items-center">
                                        <svg class="w-4 h-4 mr-2 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                        {{ $event->date->format('M d, Y') }} • {{ $event->time->format('h:i A') }}
                                    </div>
                                    <div class="flex items-center">
                                        <svg class="w-4 h-4 mr-2 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                        {{ $event->location }}
                                    </div>
                                </div>
                            </div>
                            
                            <div class="p-5 pt-0 mt-auto">
                                <div class="flex justify-between items-center mb-3 text-xs font-medium text-slate-500">
                                    <span>{{ $event->registration_fee > 0 ? '₹' . number_format($event->registration_fee, 2) : 'Free Entry' }}</span>
                                    @if($event->registration_status === 'closed')
                                        <span class="text-slate-500">Event Closed</span>
                                    @else
                                        <span class="text-teal-700">{{ max(0, $event->max_participants - $event->registrations()->count()) }} seats left</span>
                                    @endif
                                </div>
                                <a href="{{ route('events.show', $event) }}" class="block w-full text-center bg-teal-700 hover:bg-teal-600 text-white font-medium py-2.5 rounded-lg text-sm transition duration-150">
                                    View Details
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
