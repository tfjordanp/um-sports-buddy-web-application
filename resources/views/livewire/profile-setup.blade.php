<div class="max-w-4xl mx-auto py-10">
    <h1 class="text-3xl font-bold mb-6">Complete Your Profile</h1>

    <form wire:submit.prevent="saveProfile" class="space-y-6">

        {{-- Section 1: Default Location and Picture --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                {{-- Use Flux Select for Location --}}
                <flux:select wire:model.live="countryId" label="Country" id="countryId">
                    <option value="" selected>Select a Country...</option>
                    <!--<option value="" disabled selected>Select a country</option>-->
                    {{-- Assumes $locations is passed from the component logic --}}
                    @foreach($countries as $country)
                        <flux:select.option value="{{ $country->id }}">{{ $country->name }}</flux:select.option>
                    @endforeach
                </flux:select>
                @error('countryId') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <div x-data="{ showModal: @entangle('countryId') }">
                <div x-show="showModal">
                    {{-- Use Flux Select for Location --}}
                    <flux:select wire:model.live="stateId" label="State" id="stateId">
                        <option value="" selected>Select a State...</option>

                        {{-- Assumes $locations is passed from the component logic --}}
                        @foreach($this->queryStates() as $state)
                            <flux:select.option value="{{ $state->id }}">{{ $state->name }}</flux:select.option>
                        @endforeach
                    </flux:select>
                    @error('stateId') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>
            </div>
            
            <div x-data="{ showModal: @entangle('stateId') }">
                <div x-show="showModal">
                    {{-- Use Flux Select for Location --}}
                    <flux:select wire:model.live="cityId" label="City" id="cityId">
                        <option value="" selected>Select a City...</option>

                        {{-- Assumes $locations is passed from the component logic --}}
                        @foreach($this->queryCities() as $city)
                            <option value="{{ $city->id }}">{{ $city->name }}</option>
                        @endforeach
                    </flux:select>
                    @error('cityId') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>
            </div>

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

    @script
    <script>
        ['#countryId','#stateId','#cityId'].forEach(id => {
            if (!document.querySelector(id))    return ;
            document.querySelector(id).onclick = e => {
                document.querySelector(id).firstElementChild.disabled = true;
            }
        });
        //console.log('EXEC');
        $wire.on('didMount', async () => {
            /*console.log('didMount');
            ['#countryId','#stateId','#cityId'].forEach(id => {
                if (!document.querySelector(id))    return ;
                document.querySelector(id).onclick = e => {
                    document.querySelector(id).firstElementChild.disabled = true;
                }
            });*/
            
            /*//console.log('Livewire component mounted on client side, running API call...');
            const apiUrl = $wire.apiUrl;

            await fetch(`${apiUrl}/countries/`)*/
        });

         /*document.addEventListener('livewire:update',async (e) => {
            console.log('country');
            ['#countryId','#stateId','#cityId'].forEach(id => {
                if (!document.querySelector(id))    return ;
                document.querySelector(id).onclick = e => {
                    document.querySelector(id).firstElementChild.disabled = true;
                }
            });
         });*/
    </script>
    @endscript
</div>