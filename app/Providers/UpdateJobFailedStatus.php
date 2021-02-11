<?php

namespace App\Providers;

use App\Providers\LogJobFailed;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class UpdateJobFailedStatus
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
     * @param  LogJobFailed  $event
     * @return void
     */
    public function handle(LogJobFailed $event)
    {
        //
    }
}
