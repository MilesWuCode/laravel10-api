<?php

namespace App\Events;

use App\Models\User;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Queue\SerializesModels;

class FavoriteReactionEvent implements ShouldBroadcast
{
    use SerializesModels;

    /**
     * Create a new event instance.
     */
    public function __construct(public User $user, public string $model, public int $id, public bool $state)
    {

    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('user.'.$this->user->id),
        ];
    }

    public function broadcastWith(): array
    {
        return [
            'id' => $this->id,
            'model' => $this->model,
            'state' => $this->state,
        ];
    }

    /**
     * 自訂listen名
     */
    // public function broadcastAs(): string
    // {
    //     return 'server.created';
    // }
}
