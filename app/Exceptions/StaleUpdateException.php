<?php

namespace App\Exceptions;

use Illuminate\Database\Eloquent\Model;
use Symfony\Component\HttpKernel\Exception\ConflictHttpException;

// Thrown when a write's client_known_updated_at no longer matches the
// record in the database - someone else (the partner, or another device)
// already changed it. Carries the fresh model so the client can update its
// local view without a second round trip.
class StaleUpdateException extends ConflictHttpException
{
    public function __construct(public readonly Model $current)
    {
        parent::__construct('Cette fiche a été modifiée entre-temps.');
    }
}
