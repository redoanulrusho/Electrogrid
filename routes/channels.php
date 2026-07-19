<?php

use Illuminate\Support\Facades\Broadcast;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Only private/presence channels need authorization rules here.
| Public channels (e.g. "feeder.{id}") are handled by Pusher automatically
| and do NOT require a Broadcast::channel() entry.
|
*/

// Private channel: only the complaint-owning consumer may listen.
// Ensures one consumer cannot eavesdrop on another's ticket updates.
Broadcast::channel('user.{id}.complaints', function ($user, $id) {
    return (int) $user->id === (int) $id;
});
