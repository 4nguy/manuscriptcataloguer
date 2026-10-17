<?php

use Livewire\Component;

new class extends Component {
    public array $collections = [];

    public function navigateToCreateCollection(): void {
        $this->redirectRoute('admin.collections.create');
    }
};
?>

<div>
    <flux:button wire:click="navigateToCreateCollection">Create</flux:button>

    @forelse ($collections as $collection)
        <flux:text>{{ var_dump($collection) }}</flux:text>
    @empty
        <flux:text>No collections</flux:text>
    @endforelse
</div>
