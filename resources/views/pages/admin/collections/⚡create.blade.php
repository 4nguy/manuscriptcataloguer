<?php

use App\Livewire\Forms\admin\collections\CollectionForm;
use Flux\Flux;
use Livewire\Component;

new class extends Component {
    public CollectionForm $collectionForm;

    public function save(): void
    {
        try {
            $this->collectionForm->store();
        } catch (Exception $ex) {
            Flux::toast(
                variant: 'danger',
                heading: '',
                text: $ex->getMessage());
        }

    }
};

?>

<div>
    <form wire:submit="save">
        <flux:input type="text" label="Name" wire:model="collectionForm.name" />
        <flux:input type="text" label="Description" wire:model="collectionForm.description" />


        <flux:file-upload wire:model="collectionForm.photo" label="Upload files">
            <flux:file-upload.dropzone
                heading="Drop files here or click to browse"
                text="JPG, PNG, GIF up to 10MB"
            />
        </flux:file-upload>

        <div class="mt-4 flex flex-col gap-2">
            <flux:file-item
                heading="Profile_pic.jpg"
                image="https://fluxui.dev/img/demo/user.png"
                size="162400"
            >
                <x-slot name="actions">
                    <flux:file-item.remove />
                </x-slot>
            </flux:file-item>
        </div>

        <flux:button variant="primary" type="submit">Save</flux:button>
    </form>
</div>
