<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-slate-900 leading-tight">
            {{ __('Event Approvals') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-slate-900">
                    <h3 class="text-lg font-medium text-slate-900 mb-4">All Events</h3>
                    
                    @if($events->isEmpty())
                        <div class="text-center py-8 text-gray-500">No events found in the system.</div>
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-slate-200">
                                <thead class="bg-slate-50">
                                    <tr>
                                        <th scope="col" class="px-4 py-2 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Event Name</th>
                                        <th scope="col" class="px-4 py-2 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Organizer</th>
                                        <th scope="col" class="px-4 py-2 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Date</th>
                                        <th scope="col" class="px-4 py-2 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Status</th>
                                        <th scope="col" class="px-4 py-2 text-right text-xs font-medium text-slate-500 uppercase tracking-wider">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-slate-200">
                                    @foreach($events as $event)
                                        <tr class="hover:bg-slate-50">
                                            <td class="px-4 py-2 whitespace-nowrap">
                                                <div class="text-sm font-medium text-slate-900">
                                                    <a href="{{ route('events.show', $event) }}" class="hover:text-teal-600 hover:underline">{{ $event->title }}</a>
                                                </div>
                                            </td>
                                            <td class="px-4 py-2 whitespace-nowrap">
                                                <div class="text-sm text-slate-500">{{ $event->organizer->name }}</div>
                                            </td>
                                            <td class="px-4 py-2 whitespace-nowrap">
                                                <div class="text-sm text-slate-900">{{ $event->date->format('M d, Y') }}</div>
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
                                            <td class="px-4 py-2 whitespace-nowrap text-right text-sm font-medium">
                                                <a href="{{ route('admin.events.show', $event) }}" class="text-teal-700 hover:text-teal-900">View Details</a>
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
