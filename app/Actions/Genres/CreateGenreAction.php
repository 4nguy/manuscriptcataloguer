<?php

namespace App\Actions\Genres;

use App\Models\Genre;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class CreateGenreAction
{
    /**
     * Execute the action to create a new manuscript collection.
     */
    public function handle(User $user, array $attributes): Genre
    {
        DB::beginTransaction();

        try {
            $genre = Genre::create([
                'created_by' => $user->id,
                'name' => $attributes['name'],
                'description' => $attributes['description'],
            ]);

            if (! $genre) {
                throw new \RuntimeException('Failed to create the genre.');
            }

            DB::commit();

            return $genre;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
