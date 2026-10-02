<?php

namespace App\Livewire\Forms\admin\collections;

use App\Models\Collection;
use Livewire\Attributes\Validate;
use Livewire\Form;

class CollectionForm extends Form
{
    #[Validate('required|min:5|max:255')]
    public string $name = '';
    public string $description = '';
    #[Validate('min:5|max:255')]
    public string $location = '';
    #[Validate('image|max:1024')] // 1MB Max
    public $photo;
    public string $image_path = '';


    public function store()
    {
        $this->validate();

        Collection::create($this->pull());
    }
}
