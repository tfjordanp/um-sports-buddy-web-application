<!-- resources/views/livewire/event-manager.blade.php -->
<div class="min-h-screen" style="background-image: url('{{ asset("images/sports-bg.jpeg") }}'); background-size: cover; background-position: center;">
    <div class="bg-white bg-opacity-90 p-8">
        <!-- ... (Flashes, Location Selector HTML remain the same) ... -->
        <h1 class="text-3xl mb-4">Sports Events</h1>

        @if (session()->has('message'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4">
                {{ session('message') }}
            </div>
        @endif
        @if (session()->has('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">
                {{ session('error') }}
            </div>
        @endif

        <!-- Event List -->
        <div class="space-y-4">
            @foreach($events as $event)
                <div class="p-4 border rounded-lg shadow-sm bg-white">
                    <div class="flex space-x-4" style="justify-content: space-between; align-items: center;">
                        <!-- Event Image -->
                        @if(true)
                            <img style="width: 24x; height: 24px;" src="{{/* Storage::url($event->profile_picture_url) || */$event->sport->profile_picture_url }}" alt="{{ $event->title }}" class="w-24 h-24 object-cover rounded-lg">
                        @else
                            <div class="w-24 h-24 bg-gray-200 flex items-center justify-center rounded-lg">No Image</div>
                        @endif
                        
                        <div class="flex-grow">
                            <h2 class="text-xl font-semibold">{{ $event->title }}</h2>
                            <p class="text-sm text-gray-600">
                                <strong>Sport:</strong> {{ $event->sport->name ?? 'N/A' }}<br>
                                <strong>When:</strong> {{ $event->scheduled_date_time->format('Y-m-d') }}<br>
                                <strong>Where:</strong> {{ $event->location_details }}
                            </p>
                            <!--<p class="mt-1">{{ $event->description }}</p>-->
                            <p class="text-sm text-gray-500">
                                <b>Organizer:</b> {{ $event->organizer->name ?? 'N/A' }} <br>
                                {{ $event->users->count() }} / {{ $event->max_participants }} attendees
                            </p>
                        </div>
                    </div>

                    <!-- ... (Apply/Unapply/Full logic and buttons remain the same) ... -->
                    <div class="mt-2">
                        @php
                            $isFull = $event->users->count() >= $event->max_participants;
                            $hasApplied = Auth::user()->events()->where('event_id', $event->id)->exists();
                        @endphp
                        
                        @if ($hasApplied)
                            <button wire:click="unapplyFromEvent({{ $event->id }})" class="bg-red-500 hover:bg-red-600 text-white font-bold py-2 px-4 rounded">
                                Unapply
                            </button>
                        @elseif ($isFull)
                            <span class="text-red-500">Event Full</span>
                        @else
                            <button wire:click="applyToEvent({{ $event->id }})" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded">
                                Apply
                            </button>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Create Event Button/Form -->
        <button wire:click="$toggle('isCreatingEvent')" class="mt-6 bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-4 rounded">
            {{ $isCreatingEvent ? 'Cancel Create Event' : 'Create New Event' }}
        </button>

        @if($isCreatingEvent)
            <div class="mt-4 p-4 border rounded-lg shadow-lg bg-white">
                <h2 class="text-xl mb-2">New Event Form</h2>
                <form wire:submit.prevent="createEvent">
                    <!-- Title -->
                    <div class="mb-3">
                        <label for="title">Title:</label>
                        <input type="text" wire:model="newEventTitle" id="title" class="form-input mt-1 block w-full" required>
                        @error('newEventTitle') <span class="text-red-500">{{ $message }}</span> @enderror
                    </div>
                    
                    <!-- Description -->
                    <div class="mb-3">
                        <label for="description">Description:</label>
                        <textarea wire:model="newEventDescription" id="description" class="form-textarea mt-1 block w-full" required></textarea>
                        @error('newEventDescription') <span class="text-red-500">{{ $message }}</span> @enderror
                    </div>

                    <!-- Scheduled Date/Time -->
                    <div class="mb-3">
                        <label for="scheduled_date_time">Date & Time:</label>
                        <!-- Note: 'datetime-local' requires a specific format that Carbon handles in the model cast -->
                        <input type="datetime-local" wire:model="newEventScheduledDateTime" id="scheduled_date_time" class="form-input mt-1 block w-full" required>
                        @error('newEventScheduledDateTime') <span class="text-red-500">{{ $message }}</span> @enderror
                    </div>
                    
                    <!-- Capacity -->
                    <div class="mb-3">
                        <label for="capacity">Capacity:</label>
                        <input type="number" wire:model="newEventCapacity" id="capacity" class="form-input mt-1 block w-full" min="1" required>
                        @error('newEventCapacity') <span class="text-red-500">{{ $message }}</span> @enderror
                    </div>

                    <!-- Location Details (Specific address/field location) -->
                    <div class="mb-3">
                        <label for="location_details">Specific Location Details:</label>
                        <input type="text" wire:model="newEventLocationDetails" id="location_details" class="form-input mt-1 block w-full" placeholder="e.g., Field 4 at City Park" required>
                        @error('newEventLocationDetails') <span class="text-red-500">{{ $message }}</span> @enderror
                    </div>

                    <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded">
                        Save Event
                    </button>
                </form>
            </div>
        @endif
    </div>
</div>
