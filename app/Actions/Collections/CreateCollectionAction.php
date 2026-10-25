<?php

namespace App\Actions\Collections;

use App\Models\Collection;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class CreateCollectionAction
{
    /**
     * Execute the action to create a new manuscript collection.
     */
    public function handle(User $user, array $attributes): Collection
    {
        DB::beginTransaction();

        try {
            $collection = Collection::create([
                'created_by' => $user->id,
                'name' => $attributes['name'],
                'description' => $attributes['description'],
                'location' => $attributes['location'],
            ]);

            if (! $collection) {
                throw new \RuntimeException('Failed to create the collection.');
            }

            // Handle photo upload if provided
            if (isset($attributes['photo']) && $attributes['photo']) {
                try {
                    $photoPath = Storage::disk('public')->putFile('images/collections', $attributes['photo']);
                    $collection->update(['image_path' => Storage::url($photoPath)]);
                } catch (\Exception $e) {
                    // If photo upload fails, roll back the transaction
                    DB::rollback();
                    throw new \RuntimeException('Failed to upload the collection photo.', 0, $e);
                }
            }

            DB::commit();

            return $collection;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
