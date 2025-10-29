<?php

namespace App\Listeners;

use App\Events\SendClientNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendClientNotificationListener
{
    /**
     * Create the event listener.
     */
    public function __construct(SendClientNotification $event)
    {
        $client= $event->client;

        // \Mail::to($client->email)->send(new NotificationMail($client));
    }

    /**
     * Handle the event.
     */
    public function handle(SendClientNotification $event): void
    {
        //
    }
}
