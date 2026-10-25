<?php

use App\Models\Collection;
use App\Models\Genre;
use App\Models\User;
use Database\Seeders\CollectionSeeder;
use Database\Seeders\DatabaseSeeder;
use Database\Seeders\GenreSeeder;
use Database\Seeders\UserSeeder;
use Illuminate\Support\Facades\Hash;

test('database seeder seeds collections, genres, and users', function () {
    expect(Collection::count())->toBe(0)
        ->and(Genre::count())->toBe(0)
        ->and(User::count())->toBe(0);

    $this->seed(DatabaseSeeder::class);

    $frSawiris = User::where('email', 'frsawiris@manuscriptcataloguer')->first();

    expect(Collection::where('name', 'St Paul Manuscripts')->exists())->toBeTrue()
        ->and(Collection::count())->toBe(1)
        ->and(Genre::count())->toBe(4)
        ->and(Genre::whereIn('name', ['Biblical', 'Liturgical', 'Theological', 'Miscellaneous'])->count())->toBe(4)
        ->and(User::where('email', 'test@example.com')->exists())->toBeTrue()
        ->and($frSawiris)->not->toBeNull()
        ->and($frSawiris->name)->toBe('Fr. Sawiris')
        ->and($frSawiris->email_verified_at)->not->toBeNull()
        ->and(Hash::check('Gundi..ticsh19', $frSawiris->password))->toBeTrue();
});

test('collection seeder creates st paul manuscripts collection and is idempotent', function () {
    $this->seed(CollectionSeeder::class);

    $collection = Collection::where('name', 'St Paul Manuscripts')->first();
    expect($collection)->not->toBeNull()
        ->and($collection->description)->toBe('Collection of St Paul Manuscripts')
        ->and(Collection::count())->toBe(1);

    // Re-run seeder to verify idempotency
    $this->seed(CollectionSeeder::class);

    expect(Collection::count())->toBe(1)
        ->and(Collection::where('name', 'St Paul Manuscripts')->count())->toBe(1);
});

test('genre seeder creates standard genres and is idempotent', function () {
    $this->seed(GenreSeeder::class);

    $expectedGenres = [
        'Biblical' => 'Biblical texts and manuscripts',
        'Liturgical' => 'Liturgical texts and rites',
        'Theological' => 'Theological treatises and commentaries',
        'Miscellaneous' => 'Miscellaneous manuscripts and texts',
    ];

    expect(Genre::count())->toBe(4);

    foreach ($expectedGenres as $name => $description) {
        $genre = Genre::where('name', $name)->first();
        expect($genre)->not->toBeNull()
            ->and($genre->description)->toBe($description);
    }

    // Re-run seeder to verify idempotency
    $this->seed(GenreSeeder::class);

    expect(Genre::count())->toBe(4);
});

test('user seeder creates fr sawiris user with correct password and is idempotent', function () {
    $this->seed(UserSeeder::class);

    $user = User::where('email', 'frsawiris@manuscriptcataloguer')->first();
    expect($user)->not->toBeNull()
        ->and($user->name)->toBe('Fr. Sawiris')
        ->and($user->email_verified_at)->not->toBeNull()
        ->and(Hash::check('Gundi..ticsh19', $user->password))->toBeTrue()
        ->and(User::count())->toBe(1);

    // Re-run seeder to verify idempotency
    $this->seed(UserSeeder::class);

    expect(User::count())->toBe(1)
        ->and(User::where('email', 'frsawiris@manuscriptcataloguer')->count())->toBe(1);
});

test('database seeder can be run multiple times idempotently', function () {
    $this->seed(DatabaseSeeder::class);
    $this->seed(DatabaseSeeder::class);

    expect(Collection::count())->toBe(1)
        ->and(Collection::where('name', 'St Paul Manuscripts')->count())->toBe(1)
        ->and(Genre::count())->toBe(4)
        ->and(Genre::whereIn('name', ['Biblical', 'Liturgical', 'Theological', 'Miscellaneous'])->count())->toBe(4)
        ->and(User::where('email', 'test@example.com')->count())->toBe(1)
        ->and(User::where('email', 'frsawiris@manuscriptcataloguer')->count())->toBe(1);
});
