<?php

use App\Models\Collection;
use App\Models\Genre;
use App\Models\User;
use Livewire\Livewire;

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->actingAs($this->user);
});

test('genre select modal reflects updated selected genres when genre is removed in parent', function () {
    $collection = Collection::create(['name' => 'Test Collection', 'description' => 'Test Desc']);
    $genre1 = Genre::create(['name' => 'Theology', 'description' => 'Theology Desc']);
    $genre2 = Genre::create(['name' => 'Philosophy', 'description' => 'Philosophy Desc']);

    $parent = Livewire::test('pages::admin.manuscripts.create', ['collection_id' => $collection->id])
        ->set('selectedGenres', [
            $genre1->id => 'major',
            $genre2->id => 'minor',
        ]);

    expect($parent->get('selectedGenres'))->toHaveCount(2);

    // Assert rendered HTML contains the dynamic wire:key corresponding to selected genres
    $expectedKey = 'genre-select-'.md5(json_encode([$genre1->id => 'major', $genre2->id => 'minor']));
    $parent->assertSee($expectedKey);

    // Remove genre1 in parent
    $parent->call('removeGenre', (string) $genre1->id);

    expect($parent->get('selectedGenres'))->toEqual([
        $genre2->id => 'minor',
    ]);

    $newExpectedKey = 'genre-select-'.md5(json_encode([$genre2->id => 'minor']));
    $parent->assertSee($newExpectedKey);
});

test('genre select child component can remove genre selection', function () {
    $genre1 = Genre::create(['name' => 'Theology', 'description' => 'Theology Desc']);
    $genre2 = Genre::create(['name' => 'Philosophy', 'description' => 'Philosophy Desc']);

    $child = Livewire::test('admin.genres.genre-select', [
        'parentComponent' => 'pages::admin.manuscripts.create',
        'selectedGenres' => [
            $genre1->id => 'major',
            $genre2->id => 'minor',
        ],
    ]);

    expect($child->get('selectedGenres'))->toEqual([
        $genre1->id => 'major',
        $genre2->id => 'minor',
    ]);

    $child->call('removeGenre', (string) $genre1->id);

    expect($child->get('selectedGenres'))->toEqual([
        $genre2->id => 'minor',
    ]);
});

test('closing modal dispatches genre-select-modal-closed to parent component', function () {
    $genre1 = Genre::create(['name' => 'Theology', 'description' => 'Theology Desc']);

    $child = Livewire::test('admin.genres.genre-select', [
        'parentComponent' => 'pages::admin.manuscripts.create',
        'selectedGenres' => [
            $genre1->id => 'major',
        ],
    ]);

    $child->call('closeModel')
        ->assertDispatched('genre-select-modal-closed', selectedGenres: [
            $genre1->id => 'major',
        ]);
});

test('parent component updates selectedGenres when genre-select-modal-closed event is dispatched', function () {
    $collection = Collection::create(['name' => 'Test Collection', 'description' => 'Test Desc']);
    $genre1 = Genre::create(['name' => 'Theology', 'description' => 'Theology Desc']);

    $parent = Livewire::test('pages::admin.manuscripts.create', ['collection_id' => $collection->id]);

    $parent->dispatch('genre-select-modal-closed', selectedGenres: [
        $genre1->id => 'major',
    ]);

    expect($parent->get('selectedGenres'))->toEqual([
        $genre1->id => 'major',
    ]);
});
