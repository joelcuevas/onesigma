<?php

namespace App\Models\Traits;

use App\Models\Identity;

trait HasIdentities
{
    public function identities()
    {
        return $this->morphMany(Identity::class, 'identifiable');
    }

    public static function scopeWhereIdentity($query, $source, $sourceId)
    {
        $query->whereRelation('identities', 'source', $source)
            ->whereRelation('identities', 'source_id', $sourceId);
    }
}
