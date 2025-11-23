<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Livewire\WithFileUploads; // Required trait for handling file uploads (profile picture)
use App\Models\City;
use App\Models\State;
use App\Models\Country;
use App\Models\Sport;

class ProfileSetup extends Component
{
    use WithFileUploads;

    // Public properties bind automatically to the front-end view inputs (wire:model)
    public $profilePicture;
    // selectedSports is an associative array that stores sport_id => level
    public $selectedSports = []; 

    // Properties to hold data collections for dropdowns
    public $countries;
    public $sports;

    public $countryId;
    public $stateId;
    public $cityId;


    /**
     * Mounts the component, loading initial data from the database.
     */
    public function mount()
    {
        $this->countries = Country::all();
        $this->sports = Sport::all();
    }

    public function __invoke(){
        return true;
    }


    /**
     * Define validation rules for the form submission.
     */
    protected function rules()
    {
        return [
            'countryId' => 'required|exists:country,id',
            'stateId' => 'required|exists:state,id',
            'cityId' => 'required|exists:city,id',
            'profilePicture' => 'nullable|image|max:256', // Max 256kb file size
            'selectedSports.*.level' => '|numeric|min:1|max:4',
        ];
    }

    public function queryStates(){
        return State::where('country_id','=',$this->countryId);
    }

    public function queryCities(){
        return City::where('state_id','=',$this->stateId);
    }

    /**
     * Handles the form submission and saves the profile data.
     */
    public function saveProfile()
    {
        $this->validate();

        $user = Auth::user();
        
        // 1. Handle Profile Picture Upload
        if ($this->profilePicture) {
            $path = $this->profilePicture->store('profile-photos', 'public');
            $user->profile_picture = $path;
        }

        // 2. Set the default location
        $user->location_id = $this->cityId;
        $user->save(); // Save the location_id and picture path to the users table

        // 3. Sync Preferred Sports and Levels
        // Filter out sports where the user didn't select a level, then format for the pivot table
        $sportsDataToSync = collect($this->selectedSports)
            ->filter(fn ($s) => !empty($s['level']))
            ->map(fn ($s) => ['level' => $s['level']])
            ->toArray();
            
        // Use sync() to manage the many-to-many relationship in the user_sports pivot table
        $user->sports()->sync($sportsDataToSync);

        // 4. Redirect the user to the main event dashboard
        session()->flash('message', 'Profile setup complete! Welcome.');

        return redirect()->route('events.dashboard');
    }

    /**
     * Render the component's view.
     */
    public function render()
    {
        return view('livewire.profile-setup');
    }
}
