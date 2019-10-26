<?php

namespace App\Listeners;

class LogSentMessage
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
     * @param  object  $event
     * @return void
     */
    public function handle($event)
    {
        if(isset($event->data['tempFile']) && file_exists($event->data['tempFile'])) {
            unlink($event->data['tempFile']);
        }
    }
}
