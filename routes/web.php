<?php

use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome')->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::view('dashboard', 'dashboard')->name('dashboard');
    Route::livewire('/collections', 'pages::admin.collections.⚡index')->name('admin.collections.index');
    Route::livewire('/collections/create', 'admin.collections.⚡create')->name('admin.collections.create');
    Route::livewire('/collections/{$id}', 'components.admin.collections.⚡edit')->name('admin.collections.edit');


//    Route::livewire('/genres', 'components.admin.genres.⚡show')->name('admin.genres.show');
//    Route::livewire('/genres/form', 'components.admin.genres.⚡form')->name('admin.genres.form');
//    Route::livewire('/manuscripts', 'components.admin.manuscripts.⚡show')->name('admin.manuscripts.show');
//    Route::livewire('/manuscripts/form', 'components.admin.manuscripts.⚡form')->name('admin.manuscripts.⚡form');
});

require __DIR__.'/settings.php';
