<?php

namespace App\Providers;

use App\Providers\LogJobProcessed;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class UpdateJobProcessedStatus
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  LogJobProcessed  $event
     * @return void
     */
    public function handle(LogJobProcessed $event)
    {
        //
    }
}
