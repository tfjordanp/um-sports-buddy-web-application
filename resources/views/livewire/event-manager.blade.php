<!-- resources/views/livewire/event-manager.blade.php -->
<div class="min-h-screen" style="background-image: url('{{ asset("images/sports-bg.jpeg") }}'); background-size: cover; background-position: center;">
    <div class="bg-white bg-opacity-90 p-8">
        <!-- ... (Flashes, Location Selector HTML remain the same) ... -->
        <div style="display: flex; justify-content: space-between; width: 100%; margin-bottom: 2rem;">
            <h1 class="text-5xl mb-6 font-bold text-gray-800">Sports Events</h1>
            <div style="max-width: 25rem;">
                <span><!--<b>Favorite Sports: </b>-->{{ Auth::user()->preferredSports->map(function($sport){return $sport->name;})->implode(', ') }}</span><br>
                <span><b>Location: </b>{{ Auth::user()->locationStr() }}</span><br>
                <span>Applied for <b>{{ Auth::user()->events()->count() }}</b> event(s)</span><br>
                <span>Created <b>{{ Auth::user()->organizedEvents()->count() }}</b> event(s)</span><br>
            </div>
        </div>

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
                            $isOwn = $event->organizer_id == Auth::user()->id;
                        @endphp
                        
                        @if ($hasApplied)
                            <button wire:click="unapplyFromEvent({{ $event->id }})" class="bg-red-500 hover:bg-red-600 text-white font-bold py-2 px-4 rounded">
                                Unapply
                            </button>
                        @elseif ($isFull)
                            <span class="text-red-500">Event Full</span>
                        @elseif ($isOwn)
                            <button wire:click="deleteEvent({{ $event->id }})" class="bg-red-500 hover:bg-red-600 text-white font-bold py-2 px-4 rounded">
                                Delete
                            </button>
                        @else
                            <button wire:click="applyToEvent({{ $event->id }})" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded">
                                Apply
                            </button>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>

        <style>
            button#create-event:hover{
                opacity: 1;
                color: grey;
            }
        </style>

        <flux:modal.trigger name="create-event">
            <flux:button id="create-event" style="position: fixed; bottom: 2rem; right: 2rem; border-radius: 100%; width: 100px; height: 100px; background: lightgreen; cursor: pointer; opacity: 0.95;">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-10">
                    <path d="M12.75 12.75a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0ZM7.5 15.75a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5ZM8.25 17.25a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0ZM9.75 15.75a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5ZM10.5 17.25a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0ZM12 15.75a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5ZM12.75 17.25a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0ZM14.25 15.75a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5ZM15 17.25a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0ZM16.5 15.75a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5ZM15 12.75a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0ZM16.5 13.5a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5Z" />
                    <path fill-rule="evenodd" d="M6.75 2.25A.75.75 0 0 1 7.5 3v1.5h9V3A.75.75 0 0 1 18 3v1.5h.75a3 3 0 0 1 3 3v11.25a3 3 0 0 1-3 3H5.25a3 3 0 0 1-3-3V7.5a3 3 0 0 1 3-3H6V3a.75.75 0 0 1 .75-.75Zm13.5 9a1.5 1.5 0 0 0-1.5-1.5H5.25a1.5 1.5 0 0 0-1.5 1.5v7.5a1.5 1.5 0 0 0 1.5 1.5h13.5a1.5 1.5 0 0 0 1.5-1.5v-7.5Z" clip-rule="evenodd" />
                </svg>
            </flux:button>
        </flux:modal.trigger>

        <flux:modal name="create-event" class="md:w-96">
            <div class="space-y-6">
                <div>
                    <flux:heading size="lg">Organize a new event</flux:heading>
                    <flux:text class="mt-2">Invite several athletes to your sport event</flux:text>
                </div>
                <style>
                    form > div{
                        margin-bottom: 1rem;
                    }
                </style>
                <form wire:submit.prevent="createEvent">
                    <div>
                        <flux:input label="Title" wire:model="newEventTitle" placeholder="e.g Kickout Rush" required/>
                        @error('newEventTitle') <span class="text-red-500">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <flux:input label="Description" wire:model="newEventDescription" placeholder="e.g Let's exercise together"/>
                        @error('newEventDescription') <span class="text-red-500">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        @php
                            // Create a Carbon instance for the current time
                            $tommorow = Carbon\Carbon::now()->addDays(1);
                        @endphp
                        <flux:input label="Date & Time" min="{{ $tommorow->format('Y-m-d') }}" type="datetime-local" wire:model="newEventScheduledDateTime" type="date" required/>
                        @error('newEventScheduledDateTime') <span class="text-red-500">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <flux:input min="5" max="9999" value="5" label="Capacity" type="number" wire:model="newEventCapacity" required/>
                        @error('newEventCapacity') <span class="text-red-500">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <flux:input label="Specific Location Details" wire:model="newEventLocationDetails" placeholder="Stadium entry" required/>
                        @error('newEventLocationDetails') <span class="text-red-500">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <flux:select wire:model.live="newEventSportId" label="Sport" id="newEventSportId" placeholder="Select a Sport">
                            {{-- Assumes $locations is passed from the component logic --}}
                            @foreach(Auth::user()->preferredSports as $sport)
                                <flux:select.option value="{{ $sport->id }}">{{ $sport->name }}</flux:select.option>
                            @endforeach
                        </flux:select>
                        @error('newEventSportId') <span class="text-red-500">{{ "You need to select a sport" }}</span> @enderror
                    </div>
                    
                    <div class="flex">
                        <flux:spacer />
                        <flux:button type="submit" variant="primary">Create</flux:button>
                    </div>
                </form>
            </div>
        </flux:modal>
    </div>
</div>
