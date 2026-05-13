<?php

namespace App\Observers;

use App\Models\FollowUp;

class FollowUpObserver
{
    /**
     * Handle the FollowUp "created" event.
     */
    public function created(FollowUp $followUp) {
    if ($followUp->customer_id) {
        $followUp->customer->updateLastActivity();
    } elseif ($followUp->lead_id) {
        $followUp->lead->updateLastActivity();
    }
}

    /**
     * Handle the FollowUp "updated" event.
     */
    public function updated(FollowUp $followUp): void
    {
        //
    }

    /**
     * Handle the FollowUp "deleted" event.
     */
    public function deleted(FollowUp $followUp): void
    {
        //
    }

    /**
     * Handle the FollowUp "restored" event.
     */
    public function restored(FollowUp $followUp): void
    {
        //
    }

    /**
     * Handle the FollowUp "force deleted" event.
     */
    public function forceDeleted(FollowUp $followUp): void
    {
        //
    }
}
