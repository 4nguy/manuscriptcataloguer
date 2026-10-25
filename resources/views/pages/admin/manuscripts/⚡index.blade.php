<?php

use Livewire\Component;
use App\Models\Collection;
use App\Models\Manuscript;
use Livewire\WithPagination;
use Livewire\Attributes\Computed;

new class extends Component {
    use WithPagination;

    public string $collectionId = '';

    public function mount(): void
    {
        $this->collectionId = request()->route('collection_id');

        if (!$this->collectionId || !Collection::find($this->collectionId)) {
            abort(404, 'Collection not found.');
        }
    }

    #[Computed]
    public function manuscripts()
    {
        return Manuscript::orderBy('created_at', 'desc')->where('collection_id', $this->collectionId)->paginate(10);
    }

    public function navigateToCreateManuscript(): void
    {
        $this->redirectRoute('admin.manuscripts.create', ['collection_id' => $this->collectionId]);
    }

    public function navigateToEditManuscript(string $id): void
    {
        $this->redirectRoute('admin.manuscripts.edit', $id);
    }

    public function navigateToViewManuscript(string $id): void
    {
        $this->redirectRoute('admin.manuscripts.view', $id);
    }

    public function confirmDeleteManuscript(Manuscript $manuscript): void
    {
        $this->collection = $collection;
    }

    public function deleteManuscript(): void
    {
        $this->manuscript->delete();
    }
};
?>

<div>
    <flux:button wire:click="navigateToCreateManuscript">Add Manuscript</flux:button>

    <div class="grid grid-cols-4 gap-4">
        @forelse ($this->manuscripts as $manuscript)
            <flux:card body="inset">
                        {{ $manuscript->catalogue_number }}

                <flux:card.footer>
                    <flux:card.heading>{{ $manuscript->title }}</flux:card.heading>
                    <flux:card.subheading>{{ $manuscript->author }}</flux:card.subheading>

                    <flux:card.actions>
                        <flux:button size="sm" variant="ghost" icon="book-open"
                            aria-label="View manuscripts in collection"
                            wire:click="navigateToManuscriptsIndex('{{ $manuscript->id }}')" />
                        <flux:button size="sm" variant="ghost" icon="pencil" aria-label="Edit collection"
                            wire:click="navigateToEditManuscript('{{ $manuscript->id }}')" />
                        <flux:button size="sm" variant="ghost" icon="trash" aria-label="Delete collection"
                            wire:click="confirmDeleteManuscript('{{ $manuscript->id }}')" />
                    </flux:card.actions>
                </flux:card.footer>
            </flux:card>
        @empty
            <flux:text>No manuscripts</flux:text>
        @endforelse
    </div>

    <div class="mt-4">
        {{ $this->manuscripts->links() }}
    </div>
</div>
