<?php

use Livewire\Component;
use App\Models\Genre;
use Livewire\WithPagination;
use Livewire\Attributes\Computed;
use Illuminate\Pagination\LengthAwarePaginator;

new class extends Component {
    use WithPagination;

    public string $sortBy = 'name';
    public string $sortDirection = 'asc';

    #[Computed]
    public function genres(): LengthAwarePaginator
    {
        return Genre::query()
            ->tap(fn ($query) => $this->sortBy ? $query->orderBy($this->sortBy, $this->sortDirection) : $query)
            ->paginate(10);
    }

    public function sort($column): void
    {
        if ($this->sortBy === $column) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortBy = $column;
            $this->sortDirection = 'asc';
        }
    }

    public function navigateToCreateGenre(): void
    {
        $this->redirectRoute('admin.genres.create');
    }

    public function navigateToEditGenre(string $id): void
    {
        $this->redirectRoute('admin.genres.edit', $id);
    }

    public function navigateToManuscriptsIndex(string $collectionId): void
    {
        $this->redirectRoute('admin.manuscripts.index', ['collection_id' => $collectionId]);
    }

    public function confirmDeleteGenre(Genre $collection): void
    {
        $this->collection = $collection;
    }

    public function deleteGenre(): void
    {
        $this->collection->delete();
    }
};
?>

<div>
    <flux:button wire:click="navigateToCreateGenre">Add Genre</flux:button>

    <flux:table :paginate="$this->genres">
        <flux:table.columns>
            <flux:table.column sortable :sorted="$sortBy === 'name'" :direction="$sortDirection" wire:click="sort('name')">Genres</flux:table.column>
            <flux:table.column sortable :sorted="$sortBy === 'created_at'" :direction="$sortDirection" wire:click="sort('created_at')">Created at</flux:table.column>
            <flux:table.column>Actions</flux:table.column>
        </flux:table.columns>
        <flux:table.rows>
            @foreach ($this->genres as $genre)
                <flux:table.row :key="$genre->id">
                    <flux:table.cell class="flex items-center gap-3">
                        {{ $genre->name }}
                    </flux:table.cell>
                    <flux:table.cell class="whitespace-nowrap">{{ $genre->created_at }}</flux:table.cell>
                    <flux:table.cell class="py-0">
                        <flux:button size="sm" variant="ghost" icon="pencil" aria-label="Edit genre"
                                     wire:click="navigateToEditGenre('{{ $genre->id }}')" />
                        <flux:button size="sm" variant="ghost" icon="trash" aria-label="Delete genre"
                                     wire:click="confirmDeleteGenere('{{ $genre->id }}')" />
                    </flux:table.cell>
                </flux:table.row>
            @endforeach
        </flux:table.rows>
    </flux:table>
</div>
