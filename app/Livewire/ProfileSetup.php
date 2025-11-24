<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Livewire\WithFileUploads; // Required trait for handling file uploads (profile picture)
use App\Models\Sport;
use Illuminate\Support\Facades\Http;

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

    public $selectedSport;
    public $sportsLevelsPairs;

    public $countryId;
    public $stateId;
    public $cityId;

    public $apiUrl;

    /**
     * Mounts the component, loading initial data from the database.
     */
    public function mount()
    {
        $this->apiUrl = env('LOCATIONS_API_URL', 'http://localhost:3000/api');

        $this->sports = Sport::all();
        $this->countries = json_decode(
            Http::get("$this->apiUrl/countries/")->body()
        )->data;

        $this->sportsLevelsPairs = [];

        $this->dispatch('didMount');
    }


    /**
     * Define validation rules for the form submission.
     */
    protected function rules()
    {
        return [
            'countryId' => 'required',
            'stateId' => 'required',
            'cityId' => 'required',
            'profilePicture' => 'nullable|image|max:256', // Max 256kb file size
            'selectedSports.*.level' => '|numeric|min:1|max:4',
        ];
    }

    

    protected function attributes()
    {
        return [
            'countryId' => 'Country', 
            'stateId' => 'State', 
            'cityId' => 'City', 
        ];
    }



    public function queryStates(){
        $this->dispatch('didMount');
        if (!isset($this->countryId)){
            $this->stateId = null;
            $this->cityId = null;
            return [];
        }
        return json_decode(
            Http::get("$this->apiUrl/states/?country_id=$this->countryId")->body()
        )->data;
    }

    public function queryCities(){
        if (!isset($this->stateId)){
            $this->cityId = null;
            return [];
        }

        return json_decode(
            Http::get("$this->apiUrl/cities/?state_id=$this->stateId")->body()
        )->data;
    }

    public function addSportLevelPair()
    {
        if (empty($this->selectedSport)) {
            session()->flash('error', 'Please select a sport first.');
            return;
        }

        // Find the full Sport model instance using the ID
        $sportModel = $this->sports->firstWhere('id', $this->selectedSport);

        // Add the new item to the array managed by Livewire state
        $this->sportsLevelsPairs[] = [
            'id'    => $this->selectedSport,
            'name'  => $sportModel->name, // Access the name property
            'level' => 1 // Default level
        ];

        // Reset the selected sport dropdown after adding
        $this->selectedSport = null; 
    }

    public function getRemainingSports(){
        return $this->sports->diffUsing($this->sportsLevelsPairs, function ($obj1, $obj2) {
            return $obj1['id'] <=> $obj2['id']; // Uses the spaceship operator for comparison
        });
    }

    public function deleteFromPrefered($id){
        $this->sportsLevelsPairs = array_filter($this->sportsLevelsPairs, function ($row) use ($id) {
            return $row['id'] != $id;
        });
    }

    /**
     * Handles the form submission and saves the profile data.
     */
    public function saveProfile()
    {
        $this->validate();

        //var_dump($this->cityId,$this->stateId,$this->countryId);

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
