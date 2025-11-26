<?php

use Illuminate\Support\Facades\Route;

use Livewire\Volt\Volt;

Volt::route('/', 'pages.home')->name('home');
Volt::route('/founder', 'pages.founder')->name('founder');
Volt::route('dashboard', 'pages.dashboard.novel-list')->name('dashboard');
Volt::route('novels/create', 'pages.novels.create')->name('novels.create');
Volt::route('novels/{novel}/edit', 'pages.novels.edit')->name('novels.edit');
    Volt::route('novels/{novel}/chapters/create', 'pages.chapters.create')->name('chapters.create');
    Volt::route('novels/{novel}/chapters/{chapter}/edit', 'pages.chapters.create')->name('chapters.edit');
Volt::route('profile', 'pages.auth.profile')->name('profile');
Volt::route('/novels/{novel:slug}', 'pages.novels.show')->name('novels.show');
Volt::route('/novels/{novel:slug}/{chapter:slug}', 'pages.novels.read')->name('novels.read');
Volt::route('/@{user:username}', 'pages.users.show')->name('users.show');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

require __DIR__.'/auth.php';

Route::get('auth/google', [App\Http\Controllers\Auth\SocialiteController::class, 'redirectToGoogle'])->name('auth.google');
Route::get('auth/google/callback', [App\Http\Controllers\Auth\SocialiteController::class, 'handleGoogleCallback']);
