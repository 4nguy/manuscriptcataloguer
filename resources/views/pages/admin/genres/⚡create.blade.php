<?php

use App\Livewire\Forms\Admin\Genres\GenreForm;
use Flux\Flux;
use Livewire\Component;

new class extends Component {

    public GenreForm $genreForm;

    public function save(): void
    {
        try {
            $genre = $this->genreForm->store();
            Flux::toast(variant: 'success', heading: '', text: 'Genre created successfully.');
        } catch (Exception $ex) {
            Flux::toast(variant: 'danger', heading: '', text: $ex->getMessage());
        }
    }

    public function navigateToIndex()
    {
        return redirect()->route('admin.genres.index');
    }
};

?>

<div>
    <form wire:submit="save">
        <flux:input type="text" label="Name" wire:model="genreForm.name" />
        <flux:input type="text" label="Description" wire:model="genreForm.description" />

        <flux:button variant="primary" type="submit">Save</flux:button>
        <flux:button variant="ghost" wire:click="navigateToIndex">Cancel</flux:button>
    </form>
</div>
