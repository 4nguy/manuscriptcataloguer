<?php

use App\Models\Manuscript;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\SoftDeletes;

test('uses UUIDs and soft deletes for the manuscripts table', function () {
    $manuscript = new Manuscript;

    expect($manuscript->getTable())->toBe('manuscripts')
        ->and($manuscript->getKeyType())->toBe('string')
        ->and($manuscript->getIncrementing())->toBeFalse()
        ->and(class_uses_recursive($manuscript))->toContain(HasUuids::class, SoftDeletes::class);
});
