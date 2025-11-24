<div class="max-w-4xl mx-auto py-10">
    <x-auth-header :title="__('Complete your profile')" :description="__('Enter your location and the sports you like below to start buddying')" />

    <form wire:submit.prevent="saveProfile" class="space-y-6">

        {{-- Section 1: Default Location and Picture --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                {{-- Use Flux Select for Location --}}
                <flux:select wire:model.live="countryId" label="Country" id="countryId" placeholder="Select a Country...">
                    <!--<option value="" disabled selected>Select a country</option>-->
                    {{-- Assumes $locations is passed from the component logic --}}
                    @foreach($countries as $country)
                        <flux:select.option value="{{ $country->id }}">{{ $country->name }}</flux:select.option>
                    @endforeach
                </flux:select>
                @error('countryId') <span class="text-red-500 text-sm">You need to select a country</span> @enderror
            </div>
            
            @if ($countryId)
                <div>
                    {{-- Use Flux Select for Location --}}
                    <flux:select wire:model.live="stateId" label="State" id="stateId" placeholder="Select a State...">

                        {{-- Assumes $locations is passed from the component logic --}}
                        @foreach($this->queryStates() as $state)
                            <flux:select.option value="{{ $state->id }}">{{ $state->name }}</flux:select.option>
                        @endforeach
                    </flux:select>
                    @error('stateId') <span class="text-red-500 text-sm">You need to select a state</span> @enderror
                </div>
            @endif
            
            
            @if ($stateId)
                <div>
                    {{-- Use Flux Select for Location --}}
                    <flux:select wire:model.live="cityId" label="City" id="cityId" placeholder="Select a City...">
                        {{-- Assumes $locations is passed from the component logic --}}
                        @foreach($this->queryCities() as $city)
                            <flux:select.option value="{{ $city->id }}">{{ $city->name }}</flux:select.option>
                        @endforeach
                    </flux:select>
                    @error('cityId') <span class="text-red-500 text-sm">You need to select a city</span> @enderror
                </div>
            @endif
            
            <div>
                <!--<img id="profilePictureAvatar" style="border-radius: 10rem;" />-->
                <div>
                    {{-- Use Flux Input for File Upload (Profile Picture) --}}
                    <x-flux::input wire:model="profilePicture" label="Profile Picture (Optional)" type="file" id="profilePicture" onchange="convertFile()" />
                    @error('profilePicture') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>
            </div>
            
        </div>

        {{-- Section 2: Preferred Sports and Levels --}}
        <h2 class="text-xl font-semibold mt-8">Favorite Sports</h2>
        <div class="space-y-4">
            {{-- Assumes $sports is passed from the component logic --}}
            
            <div class="flex justify-between" style="align-items: end;">
                <flux:select label="Sport" wire:model.live="selectedSport" id="sportId" placeholder="Select a Sport..." style="flex: 1;">
                    @foreach($this->getRemainingSports() as $sport)
                        <option value="{{ $sport->id }}">{{ $sport->name }}</option>
                    @endforeach
                </flux:select>
                <div style="margin: 0 2rem;"></div>
                <flux:button wire:click="addSportLevelPair" style="flex: 1;">Add</flux:button>
            </div>

            @if ($sportsLevelsPairs != null && count($sportsLevelsPairs) > 0)
            <div class="flex items-center justify-between border rounded-md bg-white p-4" style="flex-direction: column;">
                <table style="width:100%;">
                    @foreach($sportsLevelsPairs as $sport)
                    <tr>
                        <td style="text-align: left;">
                            <label style="text-align: center;" for="{{ $sport['name'] }}" class="font-medium">{{ $sport['name'] }}</label>
                        </td>
                        
                        <td style="text-align: center;">
                            {{-- Use Flux Select for Level within the loop --}}
                            <select style=" text-align: center;" class="p-4" id="{{ $sport['name'] }}" selected={{ $sport['level'] }} wire:model="selectedSports.{{ $sport['id'] }}.level" placeholder="Select level" class="w-40">
                                <option value="1">Novice</option>
                                <option value="2">Amateur</option>
                                <option value="3">Semi Pro</option>
                                <option value="4">Pro</option>
                            </select>
                        </td>

                        <td style="text-align: right;">
                            <flux:button variant="danger" style="" wire:click="deleteFromPrefered({{ $sport['id'] }})">Delete</flux:button>
                        </td>
                    </tr>
                    @endforeach
                </table>
            </div>
            @endif
            @error('sportsLevelsPairs') <span class="text-red-500 text-sm">Add at least one favorite sport</span> @enderror
            
            
        </div>

        {{-- Use Flux Button for Submission --}}
        <x-flux::button type="submit" theme="primary" class="w-full">
            Save and Continue
        </x-flux::button>
    </form>

    <script>
        function convertFile() {
            const fileInput = document.getElementById('profilePicture');
            const file = fileInput.files[0]; 

            if (file) {
                const reader = new FileReader();
                reader.onload = function(event) {
                    const dataUri = event.target.result;
                    //console.log(dataUri);

                    document.getElementById('profilePictureAvatar').setAttribute('src',dataUri);
                };
                reader.readAsDataURL(file);
            }
        }
        //document.querySelector('#profilePicture').onchange = convertFile;
    </script>
</div>