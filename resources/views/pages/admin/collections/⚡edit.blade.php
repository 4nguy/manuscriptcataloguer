<?php

use Livewire\Component;

new class extends Component {
    public array $collections = [];
};
?>

<div>
    <flux:button wire:click="rou">Create</flux:button>

    @forelse ($collections as $collection)
        <flux:text>{{ var_dump($collection) }}</flux:text>
    @empty
        <flux:text>View/Edit/Create</flux:text>
    @endforelse
</div>
