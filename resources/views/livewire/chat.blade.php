<div>
    <div style="display: flex; gap: 20px;">

        {{-- Список користувачів --}}
        <div style="width: 200px;">
            <h3>Користувачі</h3>
            @foreach($users as $user)
                <div
                    wire:click="selectUser({{ $user->id }})"
                    style="cursor: pointer; padding: 8px;"
                >
                    {{ $user->name }}
                </div>
            @endforeach
        </div>

        {{-- Чат --}}
        <div style="flex: 1;">
            @if($receiverId)
                <div style="height: 400px; overflow-y: auto; border: 1px solid #ccc; padding: 10px;">
                    @foreach($messages as $message)
                        <div>
                            <strong>{{ $message['sender']['name'] }}</strong>:
                            {{ $message['body'] }}
                            <small>{{ $message['created_at'] }}</small>
                        </div>
                    @endforeach
                </div>

                <div style="margin-top: 10px;">
                    <input
                        wire:model="body"
                        wire:keydown.enter="send"
                        type="text"
                        placeholder="Повідомлення..."
                        style="width: 80%;"
                    />
                    <button wire:click="send">Відправити</button>
                </div>
            @else
                <p>Виберіть користувача для чату</p>
            @endif
        </div>

    </div>
</div>
<script>
    document.addEventListener('livewire:initialized', () => {
        Echo.private(`user.{{ auth()->id() }}`)
            .listen('.message.sent', (e) => {
                if (e.sender_id == @this.receiverId) {
                    @this.messages.push({
                        sender: { name: e.sender_name },
                        body: e.body,
                        created_at: e.created_at,
                    });
                }

                @this.call('markDelivered', e.id);
            });
    });
</script>
