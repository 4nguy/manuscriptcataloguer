<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Table(name: 'genres', key: 'id', keyType: 'string', incrementing: false, timestamps: true)]
class Genre extends Model
{
    use HasUuids, SoftDeletes;

    public $fillable = [
        'name',
        'description',
        'created_at',
        'updated_at',
        'deleted_at',
        'created_by',
        'updated_by',
        'deleted_by',
        'id',
    ];

    public function manuscripts(): BelongsToMany
    {
        return $this->belongsToMany(Manuscript::class);
    }
}
