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

        <style>
            /* Base Styles (Mobile First) */
            .event-grid-container {
                display: grid;
                /* 1 column by default */
                grid-template-columns: repeat(1, 1fr); 
                /* Gap between items (1.5rem is equal to Tailwind's gap-6) */
                gap: 1.5rem; 
            }

            /* Medium Breakpoint (md:grid-cols-2) */
            /* Assuming a breakpoint of 768px for medium screens (Tailwind default) */
            @media (min-width: 768px) {
                .event-grid-container {
                    /* 2 columns on medium screens and up */
                    grid-template-columns: repeat(2, 1fr); 
                }
            }

            /* Large Breakpoint (lg:grid-cols-3) */
            /* Assuming a breakpoint of 1024px for large screens (Tailwind default) */
            @media (min-width: 1024px) {
                .event-grid-container {
                    /* 3 columns on large screens and up */
                    grid-template-columns: repeat(3, 1fr);
                }
            }
        </style>

        <!-- Event List -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 event-grid-container">
            @foreach($events as $event)
                <!-- Individual Bootstrap-like Card -->
                <div class="bg-white rounded-lg shadow-lg overflow-hidden flex flex-col " style="max-width: 25rem;" >
                    
                    <!-- Card Image/Header -->
                    @if(true/*$event->profile_picture_url && Storage::disk('public')->exists($event->profile_picture_url)*/)
                        <img src="{{ /*Storage::url($event->profile_picture_url)*/ $event->sport->profile_picture_url }}" alt="{{ $event->title }}" class="h-48 w-full object-cover" style="height: 24rem">
                    @else
                        <div class="h-48 bg-gray-300 flex items-center justify-center text-gray-600">
                            No Image Available
                        </div>
                    @endif

                    <!-- Card Body -->
                    <div class="p-5 flex-grow" style="padding-left: 4px;">
                        <h2 class="text-2xl font-bold mb-2">{{ $event->title }}</h2>
                        <p class="text-sm text-gray-500 mb-4">
                            <strong>Sport:</strong> {{ $event->sport->name ?? 'N/A' }}
                        </p>
                        <p class="text-gray-700 mb-4">{{ Str::limit($event->description, 100) }}</p>

                        <ul class="text-sm text-gray-600 space-y-2">
                            <li><strong>When:</strong> {{ $event->scheduled_date_time->diffForHumans() }}</li>
                            <li><strong>Where:</strong> {{ $event->location_details }}</li>
                            <li><strong>Organizer:</strong> {{ $event->organizer->name ?? 'N/A' }}</li>
                            <li><strong>Attendees:</strong> {{ $event->users->count() }} / {{ $event->max_participants }}</li>
                        </ul>
                    </div>

                    <!-- Card Footer/Actions -->
                    <div class="mt-2" style="margin-left: 4px; margin-bottom: 4px;">
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
