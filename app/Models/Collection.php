<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Table(name: 'collections', key: 'id', keyType: 'string', incrementing: false, timestamps: true)]
class Collection extends Model
{
    use HasUuids, SoftDeletes;

    protected $fillable = [
        'name',
        'description',
        'location',
        'image_path',
        'created_at',
        'updated_at',
        'deleted_at',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    public function manuscripts(): HasMany
    {
        return $this->hasMany(Manuscript::class);
    }
}
