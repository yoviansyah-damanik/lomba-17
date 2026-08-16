<?php

use App\Models\Competition;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => Redirect::to(Auth::check() ? route('dashboard') : route('login')));

Route::get('live-rank/{competition}', fn (Competition $competition) => view('live-rank', [
    'competitionId' => $competition->id,
]))->name('live-rank.show');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth'])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::view('competitions', 'admin.competitions')->name('competitions');
    Route::get('competitions/{competition}/recap', fn (Competition $competition) => view('admin.recap', [
        'competitionId' => $competition->id,
    ]))->name('recap');
    Route::view('judges', 'admin.judges')->name('judges');
    Route::view('participants', 'admin.participants')->name('participants');
});

Route::view('evaluation', 'evaluation')
    ->middleware(['auth', 'role:judge'])
    ->name('evaluation');

Route::view('evaluation/recap', 'evaluation.recap')
    ->middleware(['auth', 'role:judge'])
    ->name('evaluation.recap');

require __DIR__.'/auth.php';
