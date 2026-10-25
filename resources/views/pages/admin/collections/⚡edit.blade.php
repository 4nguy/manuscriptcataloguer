<?php

use Livewire\Component;
use Livewire\withFileUploads;
use App\Models\Collection;
use App\Livewire\Forms\Admin\Collections\CollectionForm;
use Flux\Flux;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

new class extends Component {
    use withFileUploads;

    public CollectionForm $collectionForm;

    public function mount(string $id): void
    {
        try {
            $collection = Collection::findOrFail($id);

            $this->collectionForm->id = $collection->id;
            $this->collectionForm->name = $collection->name;
            $this->collectionForm->location = $collection->location;
            $this->collectionForm->description = $collection->description;
            $this->collectionForm->image_path = $collection->image_path;
        } catch (RecordNotFoundException $ex) {
            Flux::toast(variant: 'danger', heading: '', text: 'The collection was not found.');
            $this->redirect()->route('admin.collections.index');
        }
    }

    public function save()
    {
        if ($this->collectionForm->photo || $this->collectionForm->image_path) {
            try {
                $this->collectionForm->update();
                Flux::toast(variant: 'success', heading: '', text: 'Collection updated successfully.');
            } catch (Exception $ex) {
                Flux::toast(variant: 'danger', heading: '', text: $ex->getMessage());
            }
        } else {
            Flux::toast(variant: 'danger', heading: '', text: 'Please upload a photo or provide an image path.');
        }
    }

    public function navigateToIndex()
    {
        return redirect()->route('admin.collections.index');
    }

    public function removeUploadedPhoto()
    {
        $this->collectionForm->photo->delete();
        $this->collectionForm->photo = null;
    }

    public function removeCurrentPhoto()
    {
        $this->collectionForm->image_path = '';
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
                        <flux:file-item.remove wire:click="removeUploadedPhoto"
                            aria-label="{{ 'Remove file: ' . $collectionForm->photo->getClientOriginalName() }}" />
                    </x-slot>
                </flux:file-item>
            @elseif ($collectionForm->image_path)
                <flux:file-item heading="Current image" :image="asset($collectionForm->image_path)"
                    :size="Storage::disk('public')->size('images/collections/' . basename($collectionForm->image_path))">
                    <x-slot name="actions">
                        <flux:file-item.remove wire:click="removeCurrentPhoto"
                            aria-label="{{ 'Remove file: ' . basename($collectionForm->image_path) }}" />
                    </x-slot>
                </flux:file-item>
            @endif
        </div>

        <flux:button variant="primary" type="submit">Update</flux:button>
        <flux:button variant="ghost" wire:click="navigateToIndex">Cancel</flux:button>
    </form>
</div>
