<?php

namespace App\Actions\Manuscripts;

use App\Models\Manuscript;
use App\Models\User;

class CreateManuscriptAction
{
    /**
     * Execute the action to create a new manuscript.
     *
     * @return void
     */
    public function handle(User $user, array $attributes): Manuscript
    {
        // 1. Create a new manuscript
        return Manuscript::create([
            'created_by' => $user->id,
            'catalogue_number' => $attributes['catalogue_number'],
            'content_description' => $attributes['content_description'],
            'format' => $attributes['format'],
        ]);
    }
}
