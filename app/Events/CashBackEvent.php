<?php

namespace App\Events;

use App\Enums\CashBackDirectionEnum as Direction;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CashBackEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public Direction $directionEnum;
    public int $botId;
    public int $userId;
    public int $adminId;
    public string $info;
    public float $amount;

    /**
     * Create a new event instance.
     */
    public function __construct(
        int       $botId,
        int       $userId,
        int       $adminId,
        float    $amount,
        string    $info,
        Direction $directionEnum,
    )
    {
        $this->botId = $botId;
        $this->userId = $userId;
        $this->adminId = $adminId;
        $this->amount = $amount;
        $this->info = $info;
        $this->directionEnum = $directionEnum;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('channel-name'),
        ];
    }
}
