<div class="max-w-4xl mx-auto py-10">
    <h1 class="text-3xl font-bold mb-6">Complete Your Profile</h1>

    <form wire:submit.prevent="saveProfile" class="space-y-6">

        {{-- Section 1: Default Location and Picture --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                {{-- Use Flux Select for Location --}}
                <x-flux::select wire:model="countryId" label="Country" placeholder="Select a country" variant="combobox">
                    {{-- Assumes $locations is passed from the component logic --}}
                    @foreach($countries as $country)
                        <option value="{{ $country->id }}">{{ $country->name }}</option>
                    @endforeach
                </x-flux::select>
                @error('countryId') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>
            @if ($countryId != null)
                <div>
                    {{-- Use Flux Select for Location --}}
                    <x-flux::select wire:model="stateId" label="State" placeholder="Select a state" variant="combobox">
                        {{-- Assumes $locations is passed from the component logic --}}
                        @foreach($this->queryStates() as $state)
                            <option value="{{ $state->id }}">{{ $state->name }}</option>
                        @endforeach
                    </x-flux::select>
                    @error('stateId') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>
            @endif

            @if ($stateId != null)
                <div>
                    {{-- Use Flux Select for Location --}}
                    <x-flux::select wire:model="cityId" label="City" placeholder="Select a city" variant="combobox">
                        {{-- Assumes $locations is passed from the component logic --}}
                        @foreach($this->queryCities() as $city)
                            <option value="{{ $city->id }}">{{ $city->name }}</option>
                        @endforeach
                    </x-flux::select>
                    @error('cityId') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>
            @endif

            <div>
                {{-- Use Flux Input for File Upload (Profile Picture) --}}
                <x-flux::input wire:model="profilePicture" label="Profile Picture (Optional)" type="file" />
                @error('profilePicture') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>
        </div>

        {{-- Section 2: Preferred Sports and Levels --}}
        <h2 class="text-xl font-semibold mt-8">Preferred Sports & Levels</h2>
        <div class="space-y-4">
            {{-- Assumes $sports is passed from the component logic --}}
            @foreach($sports as $sport)
                <div class="flex items-center justify-between p-4 border rounded-md bg-white">
                    <span class="font-medium">{{ $sport->name }}</span>
                    
                    {{-- Use Flux Select for Level within the loop --}}
                    <x-flux::select wire:model="selectedSports.{{ $sport->id }}.level" placeholder="Select level" class="w-40">
                        <option value="1">Novice</option>
                        <option value="2">Amateur</option>
                        <option value="3">Semi Pro</option>
                        <option value="4">Pro</option>
                    </x-flux::select>
                </div>
            @endforeach
        </div>

        {{-- Use Flux Button for Submission --}}
        <x-flux::button type="submit" theme="primary" class="w-full">
            Save and Continue to Events
        </x-flux::button>
    </form>
</div>