<?php

namespace App\Http\Concerns;

use App\Exceptions\StaleUpdateException;
use Illuminate\Database\Eloquent\Model;

trait ChecksOptimisticConcurrency
{
    // $clientKnownUpdatedAt is the updated_at the client last saw for this
    // record (sent back on every write). If it no longer matches what's in
    // the database, someone else changed the record first - reject instead
    // of silently overwriting their change. A null value means the client
    // hasn't been updated to send it yet (or never fetched the record before
    // writing) - don't block on missing data, only on a genuine mismatch.
    protected function assertNotStale(Model $model, ?string $clientKnownUpdatedAt): void
    {
        if ($clientKnownUpdatedAt === null) {
            return;
        }

        if (!$model->updated_at->equalTo($clientKnownUpdatedAt)) {
            throw new StaleUpdateException($model->fresh());
        }
    }
}
