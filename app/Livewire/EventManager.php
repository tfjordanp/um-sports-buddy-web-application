<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Event;
use Illuminate\Support\Facades\Auth;
use Livewire\WithFileUploads; // Import the trait
use Illuminate\Support\Facades\Storage;

class EventManager extends Component
{
    use WithFileUploads; // Use the trait

    public $events = [];
    public $userLocation;
    public $selectedLocationId;
    public $isCreatingEvent = false;

    // Properties for the new event form
    public $newEventTitle = '';
    public $newEventDescription = '';
    public $newEventCapacity = 1;
    public $newEventLocationId;
    public $newEventSportId;
    public $newEventScheduledDateTime;
    public $newEventImage; // Property for file upload
    public $newEventLocationDetails = '';

    public function mount()
    {
        $this->userLocation = Auth::user()->location_id;
        $this->selectedLocationId = $this->userLocation;
        $this->loadEvents();
    }

    public function loadEvents()
    {
        $query = Event::query();

        if ($this->selectedLocationId) {
            $query->where('location_id', $this->selectedLocationId);
        }
        
        $ids = Auth::user()->preferredSports()->get()->map(function ($sp){
            return $sp->id;
        });

        $query->whereIn('sport_id',$ids);

        $query->orderBy('scheduled_date_time');

        // Order by the scheduled time, eager load relationships
        $this->events = $query->with('users', 'organizer', 'sport')
                              ->get();
    }

    // ... (applyToEvent and unapplyFromEvent methods from previous response remain the same) ...
    public function applyToEvent($eventId){
        // ... existing logic ...
        $event = Event::findOrFail($eventId);
        $user = Auth::user();

        if ($event->users()->count() >= $event->max_participants) {
            session()->flash('error', 'Sorry, this event is full.');
            return;
        }

        if ($user->events()->where('event_id', $eventId)->exists()) {
            session()->flash('error', 'You have already applied to this event.');
            return;
        }

        $user->events()->attach($eventId);
        session()->flash('message', 'Successfully applied to the event!');
        $this->loadEvents();
    }
    
    public function unapplyFromEvent($eventId){
        $event = Event::findOrFail($eventId);
        $user = Auth::user();

        if ($user->events()->where('event_id', $eventId)->exists()) {
            $user->events()->detach($eventId);
            session()->flash('message', 'Successfully unapplied from the event.');
            $this->loadEvents(); // Refresh event list
        } else {
            session()->flash('error', 'You are not applied to this event.');
        }
    }

    public function deleteEvent($eventId){
        $event = Event::findOrFail($eventId);
        $event->delete();
        $this->loadEvents(); // Refresh event list
    }

    public function createEvent()
    {
        $this->validate([
            'newEventTitle' => 'required|string|max:255',
            'newEventDescription' => 'required|string',
            'newEventCapacity' => 'required|integer|min:1',
            'newEventSportId' => 'required|exists:sports,id',
            'newEventScheduledDateTime' => 'required|date',
            'newEventLocationDetails' => 'required|string|max:255',
        ]);

        Event::create([
            'title' => $this->newEventTitle,
            'description' => $this->newEventDescription,
            'max_participants' => $this->newEventCapacity,
            'location_id' => Auth::user()->location_id,
            'sport_id' => $this->newEventSportId,
            'scheduled_date_time' => $this->newEventScheduledDateTime,
            'location_details' => $this->newEventLocationDetails,
            'organizer_id' => Auth::id(),
        ]);

        session()->flash('message', 'Event created successfully!');
        
        $this->modal('create-event')->close();

        $this->reset(['newEventTitle', 'newEventDescription', 'newEventCapacity', 'newEventLocationId', 'newEventSportId', 'newEventScheduledDateTime', 'newEventLocationDetails', 'isCreatingEvent']);
        $this->loadEvents(); // Refresh event list
    }

    public function render(){
        return view('livewire.event-manager');
    }
}
