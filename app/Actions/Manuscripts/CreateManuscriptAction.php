<?php

namespace App\Actions\Manuscripts;

use App\Models\User;

class CreateManuscriptAction
{

    /**
     * Execute the action to create a new manuscript.
     * @param User $user
     * @param array $attributes
     * @return void
     */
    public function handle(User $user, array $attributes): void
    {
        // 1. Create a new manuscript
    }
}
