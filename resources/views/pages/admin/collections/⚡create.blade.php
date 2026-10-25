<?php

use App\Livewire\Forms\Admin\Collections\CollectionForm;
use Flux\Flux;
use Livewire\Component;
use Livewire\withFileUploads;

new class extends Component {
    use withFileUploads;

    public CollectionForm $collectionForm;

    public function save(): void
    {
        try {
            $collection = $this->collectionForm->store();
            Flux::toast(variant: 'success', heading: '', text: 'Collection created successfully.');
        } catch (Exception $ex) {
            Flux::toast(variant: 'danger', heading: '', text: $ex->getMessage());
        }
    }

    public function removePhoto()
    {
        $this->collectionForm->photo->delete();
        $this->collectionForm->photo = null;
    }

    public function navigateToIndex()
    {
        return redirect()->route('admin.collections.index');
    }
};

?>

<div>
    <form wire:submit="save">
        <flux:input type="text" label="Name" wire:model="collectionForm.name" />
        <flux:input type="text" label="Location" wire:model="collectionForm.location" />
        <flux:input type="text" label="Description" wire:model="collectionForm.description" />

        <flux:file-upload wire:model="collectionForm.photo" label="Upload files">
            <flux:file-upload.dropzone heading="Drop files here or click to browse" text="JPG, PNG, GIF up to 10MB" />
        </flux:file-upload>

        <div class="mt-3 flex flex-col gap-2">
            @if ($collectionForm->photo)
                <flux:file-item :heading="$collectionForm->photo->getClientOriginalName()"
                    :image="$collectionForm->photo->temporaryUrl()" :size="$collectionForm->photo->getSize()">
                    <x-slot name="actions">
                        <flux:file-item.remove wire:click="removePhoto"
                            aria-label="{{ 'Remove file: ' . $collectionForm->photo->getClientOriginalName() }}" />
                    </x-slot>
                </flux:file-item>
            @endif
        </div>

        <flux:button variant="primary" type="submit">Save</flux:button>
        <flux:button variant="ghost" wire:click="navigateToIndex">Cancel</flux:button>
    </form>
</div>
