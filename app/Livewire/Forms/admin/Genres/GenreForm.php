<?php

namespace App\Livewire\Forms\Admin\Genres;

use App\Actions\Genres\CreateGenreAction;
use App\Models\Genre;
use Exception;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Validate;
use Livewire\Form;

class GenreForm extends Form
{
    public string $id;

    #[Validate('required|min:5|max:255')]
    public string $name = '';

    #[Validate('min:5|max:1000')]
    public string $description = '';

    /**
     * @throws Exception
     */
    public function store(): Genre
    {
        $this->validate();

        $attributes = [
            'name' => $this->name,
            'description' => $this->description,
        ];

        return app(CreateGenreAction::class)->handle(Auth::user(), $attributes);
    }

    public function update() {}
}
