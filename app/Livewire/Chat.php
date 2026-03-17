<?php

namespace App\Livewire;

use App\Events\MessageSent;
use App\Models\Message;
use App\Models\User;
use Livewire\Component;

class Chat extends Component
{
    public string $body = '';
    public ?int $receiverId = null;
    public array $messages = [];

    public function mount(): void
    {
        if ($this->receiverId) {
            $this->loadMessages();
        }
    }

    public function selectUser(int $userId): void
    {
        $this->receiverId = $userId;
        $this->loadMessages();
    }

    public function loadMessages(): void
    {
        $authId = auth()->id();

        $this->messages = Message::with('sender:id,name')
            ->where(function ($q) use ($authId) {
                $q->where('sender_id', $authId)
                    ->where('receiver_id', $this->receiverId);
            })
            ->orWhere(function ($q) use ($authId) {
                $q->where('sender_id', $this->receiverId)
                    ->where('receiver_id', $authId);
            })
            ->orderBy('created_at')
            ->get()
            ->toArray();
    }

    public function send(): void
    {
        $this->validate([
            'body'       => 'required|string|max:5000',
            'receiverId' => 'required|integer|exists:users,id',
        ]);

        $message = Message::create([
            'sender_id'   => auth()->id(),
            'receiver_id' => $this->receiverId,
            'body'        => $this->body,
            'delivered'   => false,
        ]);

        $message->load('sender:id,name');

        $this->messages[] = $message->toArray();

        $this->body = '';

        MessageSent::dispatch($message);
    }

    public function markDelivered(int $id): void
    {
        Message::where('id', $id)->update(['delivered' => true]);
    }

    public function render()
    {
        $users = User::where('id', '!=', auth()->id())
            ->orderBy('name')
            ->get(['id', 'name']);

        return view('livewire.chat', compact('users'));
    }
}
