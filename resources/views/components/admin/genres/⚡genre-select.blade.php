<?php

use App\Models\Genre;
use Illuminate\Pagination\LengthAwarePaginator;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Modelable;
use Livewire\Component;
use Livewire\WithPagination;

new class extends Component {
    use WithPagination;

    #[Modelable]
    public bool $showGenreModal = false;

    public array $selectedGenres = [];
    public string $parentComponent = '';

    public string $search = '';


    public function closeModel(): void
    {
        $this->showGenreModal = false;
        $this->dispatch("genre-select-modal-closed", selectedGenres: $this->selectedGenres)->to($this->parentComponent);
    }

    // Reset page back to 1 whenever the user updates the search input
    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function render(): \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
    {
        $genres = Genre::where('name', 'like', '%' . $this->search . '%')
            ->orderBy('name', 'asc')->get();

        return $this->view([
            'genres' => $genres,
        ]);
    }

    public function removeGenre(string $genreId): void
    {
        if (array_key_exists($genreId, $this->selectedGenres))
        {
            unset($this->selectedGenres[$genreId]);
        }
    }
};
?>

<div>
    <flux:modal name="genre-select" class="md:w-96" wire:model="showGenreModal">
        <div class="space-y-6">
            <div>
                <flux:heading size="lg">Select Genres</flux:heading>
            </div>
            <flux:input icon="magnifying-glass" wire:model.live.debounce="search"/>

            <flux:table bleed>
                @foreach($genres as $genre)
                    <flux:table.rows>
                        <flux:table.row wire:key="genre-row-{{ $genre->id }}">
                            <flux:table.cell>{{ $genre->name }}</flux:table.cell>
                            <flux:table.cell >
                                <flux:radio.group wire:model.live="selectedGenres.{{ $genre->id }}.is_major" variant="segmented" size="sm">
                                    <flux:radio value="1" label="Major" />
                                    <flux:radio value="0" label="Minor" />
                                </flux:radio.group>
                            </flux:table.cell>
                            <flux:table.cell class="cell">
                                <flux:button icon="x-mark" size="sm"
                                             variant="ghost" inset
                                             wire:click="removeGenre('{{ $genre->id }}')"
                                             :disabled="empty($selectedGenres[$genre->id])"
                                             aria-label="Remove {{ $genre->name }} selection"/>
                            </flux:table.cell>
                        </flux:table.row>
                    </flux:table.rows>
                @endforeach
            </flux:table>
            <div class="flex">
                <flux:spacer/>
                <flux:button type="button" variant="outline" wire:click="closeModel">Close</flux:button>
            </div>
        </div>
    </flux:modal>
</div>
