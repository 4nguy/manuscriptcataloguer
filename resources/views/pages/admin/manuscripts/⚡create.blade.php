<?php

use App\Livewire\Forms\Admin\Manuscripts\ManuscriptWizardPartOneForm;
use App\Models\Collection;
use App\Models\Genre;
use Flux\Flux;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\RedirectResponse;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithFileUploads;

new class extends Component {
    use WithFileUploads;

    public ManuscriptWizardPartOneForm $form;
    public array $selectedGenres = [];
    public bool $showGenreSelectModal = false;

    public function mount(string $collection_id): void
    {
        $this->form->collectionId = $collection_id;

        try {
            Collection::findOrFail($collection_id);
        } catch (ModelNotFoundException $ex) {
            Flux::toast(variant: 'danger', heading: 'Collection not found.', text: $ex->getMessage());
        }
    }

    public function save(): void
    {
        try {
            $this->form->store();
            Flux::toast(variant: 'success', heading: 'Success', text: 'Manuscript created successfully.');
        } catch (\Throwable $ex) {
            Flux::toast(variant: 'danger', heading: 'Error', text: $ex->getMessage());
        }
    }

    #[On('genre-select-modal-closed')]
    public function setSelectedGenres(array $selectedGenres): void
    {
        $this->selectedGenres = $selectedGenres;
    }

    public function removeGenre(string $genreId): void
    {
        unset($this->selectedGenres[$genreId]);
    }

    #[Computed]
    public function selectedGenresCollection(): EloquentCollection
    {
        if (empty($this->selectedGenres)) {
            return new EloquentCollection();
        }

        $genreIds = array_keys($this->selectedGenres);
        $dbGenres = Genre::whereIn('id', $genreIds)->get(['id', 'name'])->keyBy('id');

        $models = collect($this->selectedGenres)->map(function ($data, $id) use ($dbGenres) {
            $genre = $dbGenres->get($id) ?? new Genre(['id' => $id]);

            $isMajor = false;
            if (is_array($data)) {
                $isMajor = filter_var($data['is_major'] ?? false, FILTER_VALIDATE_BOOLEAN);
            } elseif (is_string($data)) {
                $isMajor = ($data === 'major');
            } elseif (is_bool($data)) {
                $isMajor = $data;
            }

            $genre->is_major = $isMajor;

            return $genre;
        });

        return new EloquentCollection($models->values());
    }

    #[Computed]
    public function majorGenres(): EloquentCollection
    {
        return $this->selectedGenresCollection->filter(fn (Genre $genre) => $genre->is_major)->values();
    }

    #[Computed]
    public function minorGenres(): EloquentCollection
    {
        return $this->selectedGenresCollection->filter(fn (Genre $genre) => ! $genre->is_major)->values();
    }

    public function navigateToIndex(): RedirectResponse
    {
        return redirect()->route('admin.collections.index');
    }
};
?>

<div>
    <form wire:submit="save">
        <flux:input type="text" label="Catalogue Number" wire:model="form.catalogueNumber" />
        <flux:textarea label="General Content Description" wire:model="form.contentDescription" />

        <flux:button type="button" wire:click="$set('showGenreSelectModal', true)">Select Genres</flux:button>

        <div>
            <flux:heading>Major Genres:</flux:heading>
            @forelse($this->majorGenres as $mjg)
                <flux:badge wire:key="major-genre-{{ $mjg->id }}">
                    {{ $mjg->name }}
                    <flux:badge.close wire:click="removeGenre('{{ $mjg->id }}')" />
                </flux:badge>
            @empty
                <flux:text class="text-zinc-500">No major genres selected.</flux:text>
            @endforelse
        </div>

        <div>
            <flux:heading>Minor Genres:</flux:heading>
            @forelse($this->minorGenres as $mig)
                <flux:badge wire:key="minor-genre-{{ $mig->id }}">
                    {{ $mig->name }}
                    <flux:badge.close wire:click="removeGenre('{{ $mig->id }}')" />
                </flux:badge>
            @empty
                <flux:text class="text-zinc-500">No minor genres selected.</flux:text>
            @endforelse
        </div>

        <flux:button variant="primary" type="submit">Save</flux:button>
        <flux:button variant="ghost" wire:click="navigateToIndex">Cancel</flux:button>
    </form>

    <livewire:admin.genres.genre-select
        wire:key="genre-select-{{ md5(json_encode($selectedGenres)) }}"
        parent-component="pages::admin.manuscripts.create"
        :selected-genres="$selectedGenres"
        wire:model="showGenreSelectModal"
    />
</div>
