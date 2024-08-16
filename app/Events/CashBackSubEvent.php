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

class CashBackSubEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public Direction $directionEnum;
    public string $title;
    public int $botId;
    public int $userId;
    public int $adminId;
    public string $info;
    public float $amount;
    public $percent;
    public $needUserReview;

    /**
     * Create a new event instance.
     */
    public function __construct(
        string       $title,
        int       $botId,
        int       $userId,
        int       $adminId,
        float    $amount,
        string    $info,
        Direction $directionEnum,
        $percent = null,
        bool $needUserReview = true,
    )
    {
        $this->title = $title;
        $this->botId = $botId;
        $this->userId = $userId;
        $this->adminId = $adminId;
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
