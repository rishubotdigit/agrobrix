<?php

namespace App\Listeners;

use App\Events\UserRegistered;
use App\Models\Notification;

class CreateNotificationForUserRegistered
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(UserRegistered $event): void
    {
        // Check if notification already exists for this user
        $exists = Notification::where('type', 'user_registered')
            ->where('data->user_id', $event->user->id)
            ->exists();

        if (!$exists) {
            Notification::create([
                'type' => 'user_registered',
                'message' => 'A new user ' . $event->user->name . ' (' . $event->user->email . ') has registered as ' . $event->user->role . '.',
                'data' => [
                    'user_id' => $event->user->id,
                ],
            ]);
        }
    }
}