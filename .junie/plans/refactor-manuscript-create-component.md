---
sessionId: session-261025-144439-1ojy
---

# Requirements

### Overview & Goals
Refactor the single-file Livewire component `resources/views/pages/admin/manuscripts/⚡create.blade.php` to maximize maintainability, readability, and operational reliability. The refactoring addresses template-to-component binding mismatches, extracts inline view calculations into computed properties, cleans up dead code and exception types, and streamlines state handling.

### Scope
- **In Scope**:
  - Refactoring PHP class imports, properties, and methods in `resources/views/pages/admin/manuscripts/⚡create.blade.php`.
  - Aligning form property bindings between `ManuscriptWizardPartOneForm` and the Blade template.
  - Extracting major and minor genre filtering into dedicated Livewire `#[Computed]` properties.
  - Ensuring complete backward compatibility with `tests/Feature/Admin/Manuscripts/ManuscriptCreateGenreTest.php`.
- **Out of Scope**:
  - Modifying the underlying database schema or migrations.
  - Altering the behavior of `admin.genres.genre-select` child component.

# Technical Design

### Key Refactorings Applied

1. **Renaming & Declaration**:
   - Renamed `public ManuscriptWizardPartOneForm $manuscriptWizardPartOneForm` to `public ManuscriptWizardPartOneForm $form` (and updated Blade bindings accordingly to `$form.catalogueNumber` and `$form.contentDescription`).
   - Removed unused imports: `App\Models\Manuscript`, `JetBrains\PhpStorm\NoReturn`.
   - Corrected `RecordNotFoundException` to `Illuminate\Database\Eloquent\ModelNotFoundException`.
   - Standardized `WithFileUploads` casing.

2. **Extract Function / Computed Properties**:
   - Extracted `majorGenres()` and `minorGenres()` as `#[Computed]` properties to remove logic-heavy `@php ... filter() @endphp` blocks from the Blade template.
   - Streamlined `selectedGenresCollection()` to cleanly resolve `is_major` whether structured as boolean, numeric string (`'1'`/`'0'`), or string (`'major'`).

3. **Exception Handling & Action Dispatching**:
   - Replaced generic un-imported `Exception` with `\Throwable` in `mount()` and `save()`.
   - Wired `save()` to invoke `$this->form->store()` and handle redirect or notifications reliably.

### Refactored Code

```Blade
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
    public ?string $collectionId = null;

    public function mount(string $collection_id): void
    {
        $this->collectionId = $collection_id;

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
```

# Delivery Steps

### ✓ Step 1: Refactor Component Class and State Management
Clean up imports, fix exception handling, and align form object declarations with Livewire form conventions.

- Remove unused imports (`Manuscript`, `NoReturn`) and correct `ModelNotFoundException` import.
- Rename the form property to `$form` and align its usage with `ManuscriptWizardPartOneForm`.
- Implement robust exception handling for `mount()` and `save()` operations using `Flux::toast`.

### ✓ Step 2: Refactor Computed Properties and Blade View Template
Extract computed properties for filtered genres and update the Blade view bindings.

- Add `majorGenres` and `minorGenres` computed properties to eliminate inline `@php` filtering blocks in the template.
- Refactor `selectedGenresCollection` logic for cleaner normalization and collection mapping.
- Update form field bindings (`$form.catalogueNumber`, `$form.contentDescription`) and render genre badges via the new computed properties.
- Verify compatibility against existing feature tests (`ManuscriptCreateGenreTest`).