<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Table(name: 'collections', key: 'uuid', keyType: 'string', incrementing: false, timestamps: true)]
class Collection extends Model
{
    use HasUuids, SoftDeletes;


    public function manuscripts(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Manuscript::class);
    }
}
