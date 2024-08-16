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

class CashBackSystemEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public Direction $directionEnum;
    public int $botId;
    public int $userId;
    public string $info;
    public float $amount;
    public $percent;
    public $needUserReview;

    /**
     * Create a new event instance.
     */
    public function __construct(
        int       $botId,
        int       $userId,

        float    $amount,
        string    $info,
        Direction $directionEnum,
        $percent = null,
        bool $needUserReview = true,
    )
    {
        $this->botId = $botId;
        $this->userId = $userId;
        $this->amount = $amount;
        $this->info = $info;
        $this->directionEnum = $directionEnum;
        $this->percent = $percent;
        $this->needUserReview = $needUserReview;
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
