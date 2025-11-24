<?php

use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Features;
use Livewire\Volt\Volt;
use App\Livewire\ProfileSetup;
use App\Http\Middleware\EnsureProfileIsComplete;

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified',EnsureProfileIsComplete::class])
    ->name('dashboard');

Route::redirect('/','/login')->name('home');

//Route::view('/profile/setup', 'profilesetup')->name('profile.setup');
/*Route::get('/profile-setup', function () {
    return view('livewire.profile-setup');
});*/

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');
    Route::get('/profile/setup', ProfileSetup::class)->name('profile.setup');

    Volt::route('settings/profile', 'settings.profile')->name('profile.edit');
    Volt::route('settings/password', 'settings.password')->name('user-password.edit');
    Volt::route('settings/appearance', 'settings.appearance')->name('appearance.edit');

    Volt::route('settings/two-factor', 'settings.two-factor')
        ->middleware(
            when(
                Features::canManageTwoFactorAuthentication()
                    && Features::optionEnabled(Features::twoFactorAuthentication(), 'confirmPassword'),
                ['password.confirm'],
                [],
            ),
        )
        ->name('two-factor.show');
});
