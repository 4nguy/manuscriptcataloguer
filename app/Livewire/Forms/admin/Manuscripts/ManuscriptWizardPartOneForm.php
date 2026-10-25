<?php

namespace App\Livewire\Forms\Admin\Manuscripts;

use App\Actions\Manuscripts\CreateManuscriptAction;
use App\Models\Collection;
use App\Models\Manuscript;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Validate;
use Livewire\Form;

class ManuscriptWizardPartOneForm extends Form
{
    public string $id;

    public string $collectionId;

    #[Validate('required|min:5|max:100')]
    public string $catalogueNumber = '';

    #[Validate('required|min:5|max:1000')]
    public string $contentDescription = '';

    #[Validate('min:5|max:255')]
    public string $format = ''; // Codex complete, Codex incomplete, Fragment

    public array $genres = [];

    public function store(): Manuscript
    {
        $this->validate();

        try {
            Collection::findOrFail($this->collectionId);
        } catch (ModelNotFoundException $ex) {
            throw new ModelNotFoundException('Collection not found.');
        }

        $attributes = [
            'collection_id' => $this->collectionId,
            'catalogue_number' => $this->catalogueNumber,
            'content_description' => $this->contentDescription,
            'format' => $this->format,
        ];

        try {
            return app(CreateManuscriptAction::class)->handle(Auth::user(), $attributes);
            // Optionally, redirect or show success message
        } catch (\Exception $e) {
            // Log the error or re-throw it
            throw $e;
        }
    }

    public function update() {}
}
