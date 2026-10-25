<?php

namespace App\Livewire\Forms\Admin\Collections;

use App\Actions\Collections\CreateCollectionAction;
use App\Models\Collection;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Validate;
use Livewire\Form;

class CollectionForm extends Form
{
    public string $id;

    #[Validate('required|min:5|max:255')]
    public string $name = '';

    #[Validate('required|min:5|max:1000')]
    public string $description = '';

    #[Validate('min:5|max:255')]
    public string $location = '';

    #[Validate('image|max:1024')] // 1MB Max
    public $photo;

    public string $image_path = '';

    public function store(): Collection
    {
        $this->validate();

        $attributes = [
            'name' => $this->name,
            'description' => $this->description,
            'location' => $this->location,
            'photo' => $this->photo,
        ];

        try {
            return app(CreateCollectionAction::class)->handle(Auth::user(), $attributes);
            // Optionally, redirect or show success message
        } catch (\Exception $e) {
            // Log the error or re-throw it
            throw $e;
        }
    }

    public function update() {}
}
