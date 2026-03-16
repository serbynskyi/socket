<?php

namespace App\Listeners;

use App\Events\MessageSent;
use App\Models\Message;
use Illuminate\Auth\Events\Login;

class SendUndeliveredMessages
{
    public function handle(Login $event): void
    {
        $messages = Message::with('sender')
            ->where('receiver_id', $event->user->id)
            ->where('delivered', false)
            ->orderBy('created_at')
            ->get();

        foreach ($messages as $message) {
            MessageSent::dispatch($message);
        }
    }
}
