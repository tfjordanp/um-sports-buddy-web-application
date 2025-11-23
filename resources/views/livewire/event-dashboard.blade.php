<div class="max-w-6xl mx-auto py-10">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold">Event Dashboard</h1>
        
        {{-- Use Flux Button to open the Create Event Modal --}}
        <x-flux::button wire:click="$dispatch('openCreateEventModal')" theme="success">
            Create Event
        </x-flux::button>
    </div>

    {{-- Filter Events by Location --}}
    <div class="mb-6">
        {{-- Use Flux Select for filtering location --}}
        <x-flux::select 
            wire:model.live="filterLocationId" 
            label="View Events In:" 
            placeholder="All Locations"
            class="w-full md:w-1/3"
        >
            {{-- Assumes $locations is available --}}
            @foreach($locations as $location)
                <option value="{{ $location->id }}">{{ $location->name }}</option>
            @endforeach
        </x-flux::select>
    </div>

    {{-- Events List (Using standard divs/cards, as Flux doesn't have a specific Card component) --}}
    <div class="space-y-4">
        @forelse($events as $event)
            <div class="p-4 border rounded-lg shadow-sm bg-white flex justify-between items-center">
                <div>
                    <h2 class="text-xl font-semibold">{{ $event->title }}</h2>
                    <p class="text-sm text-gray-500">{{ $event->location->name }} | {{ $event->sport->name }}</p>
                    <p class="text-sm text-gray-500">Hosted by {{ $event->creator->name }}</p>
                    <p class="text-sm mt-2">{{ $event->description }}</p>
                </div>

                <div>
                    {{-- Check if the current user has applied to this event, use Flux Button for action --}}
                    @if($event->isApplied())
                        <x-flux::button wire:click="unapplyFromEvent({{ $event->id }})" theme="danger">
                            Unapply
                        </x-flux::button>
                    @else
                        <x-flux::button wire:click="applyToEvent({{ $event->id }})" theme="primary">
                            Apply
                        </x-flux::button>
                    @endif
                </div>
            </div>
        @empty
            {{-- Use Flux Alert for empty state --}}
            <x-flux::alert theme="info">
                No events found in this location.
            </x-flux::alert>
        @endforelse
    </div>
    
    {{-- Placeholder for Create Event Modal component --}}
    {{-- You might trigger a Flux Modal component here --}}
</div>