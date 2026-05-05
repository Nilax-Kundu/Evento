<div class="border border-slate-200 rounded-lg p-6 relative overflow-hidden bg-white hover:-translate-y-1 hover:shadow-md transition duration-150">
    <div class="absolute top-0 right-0 {{ ($isPast ?? false) ? 'bg-slate-400' : 'bg-teal-700' }} text-white text-xs font-bold px-3 py-1 rounded-bl-lg">
        {{ ($isPast ?? false) ? 'PAST' : 'TICKET' }}
    </div>
    
    <h3 class="text-lg font-semibold text-slate-900 mb-1">
        <a href="{{ route('events.show', $registration->event) }}" class="hover:text-teal-700 hover:underline">
            {{ $registration->event->title }}
        </a>
    </h3>
    
    <div class="text-sm text-slate-500 mb-4">
        {{ $registration->event->date->format('D, M d, Y') }} at {{ $registration->event->time->format('h:i A') }}
        <br>
        {{ $registration->event->location }}
    </div>
    
    <div class="mt-4 pt-4 border-t border-slate-100">
        <p class="text-xs text-slate-500 uppercase tracking-wider mb-1">Ticket ID</p>
        <p class="font-mono text-sm text-slate-800 bg-slate-50 p-2 rounded border border-slate-200 break-all">
            {{ $registration->ticket_id }}
        </p>
    </div>
    
    <div class="mt-4 flex justify-between items-center text-sm">
        <span class="text-slate-500">Registered on: {{ $registration->created_at->format('M d, Y') }}</span>
        
        @if(!($isPast ?? false))
            <form action="{{ route('registrations.destroy', $registration) }}" method="POST" onsubmit="return confirm('Are you sure you want to cancel this registration?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="text-red-600 font-medium hover:text-red-800 transition duration-150">
                    Cancel Registration
                </button>
            </form>
        @endif
    </div>
</div>
