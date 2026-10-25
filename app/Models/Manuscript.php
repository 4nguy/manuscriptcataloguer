<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Table(name: 'manuscripts', key: 'id', keyType: 'string', incrementing: false, timestamps: true)]
class Manuscript extends Model
{
    use HasUuids, SoftDeletes;

    public $fillable = [
        'collection_id',
        'catalogue_number',
        'general_description',
        'format',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    public function collection(): BelongsTo
    {
        return $this->belongsTo(Collection::class);
    }

    public function genres(): BelongsToMany
    {
        return $this->belongsToMany(Genre::class);
    }
}
