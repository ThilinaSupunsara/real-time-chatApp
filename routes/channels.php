<?php

use App\Models\Conversation;
use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('chat.{conversationId}', function ($user, $conversationId) {
    $conversation = Conversation::find($conversationId);

    if ($conversation && $conversation->users->contains($user)) {
        // For presence channels, you must return the user's data
        return [
            'id' => $user->id,
            'name' => $user->name,
        ];
    }

    // Return null or false if the user is not part of the conversation
    return null;
});

Broadcast::channel('online', function ($user) {
    if (auth()->check()) {
        return [
            'id' => $user->id,
            'name' => $user->name,
        ];
    }
    return null;
});
