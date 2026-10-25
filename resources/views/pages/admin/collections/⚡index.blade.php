<?php

use Livewire\Component;
use App\Models\Collection;
use Livewire\WithPagination;
use Livewire\Attributes\Computed;

new class extends Component {
    use WithPagination;

    #[Computed]
    public function collections()
    {
        return Collection::orderBy('created_at', 'desc')->paginate(10);
    }

    public function navigateToCreateCollection(): void
    {
        $this->redirectRoute('admin.collections.create');
    }

    public function navigateToEditCollection(string $id): void
    {
        $this->redirectRoute('admin.collections.edit', $id);
    }

    public function navigateToManuscriptsIndex(string $collectionId): void
    {
        $this->redirectRoute('admin.manuscripts.index', ['collection_id' => $collectionId]);
    }

    public function confirmDeleteCollection(Collection $collection): void
    {
        $this->collection = $collection;
    }

    public function deleteCollection(): void
    {
        $this->collection->delete();
    }
};
?>

<div>
    <flux:button wire:click="navigateToCreateCollection">Add Collection</flux:button>

    <div class="grid grid-cols-4 gap-4">
        @forelse ($this->collections as $collection)
            <flux:card body="inset">
                <flux:card.body>
                    <flux:card.bleed>
                        <img src="{{ asset($collection->image_path) }}" alt="{{ $collection->name }}"
                            class="block aspect-square w-50 h-50 object-cover">
                    </flux:card.bleed>
                </flux:card.body>

                <flux:card.footer>
                    <flux:card.heading>{{ $collection->name }}</flux:card.heading>
                    <flux:card.subheading>{{ $collection->location }}</flux:card.subheading>

                    <flux:card.actions>
                        <flux:button size="sm" variant="ghost" icon="book-open"
                            aria-label="View manuscripts in collection"
                            wire:click="navigateToManuscriptsIndex('{{ $collection->id }}')" />
                        <flux:button size="sm" variant="ghost" icon="pencil" aria-label="Edit collection"
                            wire:click="navigateToEditCollection('{{ $collection->id }}')" />
                        <flux:button size="sm" variant="ghost" icon="trash" aria-label="Delete collection"
                            wire:click="confirmDeleteCollection('{{ $collection->id }}')" />
                    </flux:card.actions>
                </flux:card.footer>
            </flux:card>
        @empty
            <flux:text>No collections</flux:text>
        @endforelse
    </div>

    <div class="mt-4">
        {{ $this->collections->links() }}
    </div>
</div>
