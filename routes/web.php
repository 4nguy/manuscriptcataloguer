<?php

use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome')->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::view('dashboard', 'dashboard')->name('dashboard');
    Route::livewire('/collections', 'pages::admin.collections.⚡index')->name('admin.collections.index');
    Route::livewire('/collections/create', 'pages::admin.collections.⚡create')->name('admin.collections.create');
    Route::livewire('/collections/{id}/edit', 'pages::admin.collections.⚡edit')->name('admin.collections.edit');

    Route::livewire('/collections/{collection_id}/manuscripts', 'pages::admin.manuscripts.⚡index')->name('admin.manuscripts.index');
    Route::livewire('/collections/{collection_id}/manuscripts/create', 'pages::admin.manuscripts.⚡create')->name('admin.manuscripts.create');
    Route::livewire('/manuscripts/{id}/edit', 'pages::admin.manuscripts.⚡edit')->name('admin.manuscripts.edit');
    Route::livewire('/manuscripts/{id}/view', 'pages::admin.manuscripts.⚡view')->name('admin.manuscripts.view');

    Route::livewire('/genres', 'pages::admin.genres.⚡index')->name('admin.genres.index');
    Route::livewire('/genres/create', 'pages::admin.genres.⚡create')->name('admin.genres.create');
    Route::livewire('/genres/{id}/edit', 'pages::admin.genres.⚡edit')->name('admin.genres.edit');

});

require __DIR__.'/settings.php';
