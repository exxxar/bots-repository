<?php

namespace App\Http\Resources;

use App\Models\BotDialogCommand;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BotDialogCommandResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        $chain = [];

        $this->recursiveChain($this->id, $chain);

        return [
            'id' => $this->id,
            'slug' => $this->slug,
            'pre_text' => $this->pre_text,
            'post_text' => $this->post_text,
            'error_text' => $this->error_text,
            'is_empty' => $this->is_empty ?? false,
            'bot_id' => $this->bot_id,
            'chain' => $chain,
            /* 'bot' => $this->whenLoaded('bot'),*/
            'input_pattern' => $this->input_pattern,
            'inline_keyboard_id' => $this->inline_keyboard_id,
            'reply_keyboard_id' => $this->reply_keyboard_id,
            'inline_keyboard' => $this->whenLoaded('inlineKeyboard'),
            'reply_keyboard' => $this->whenLoaded('replyKeyboard'),
            'images' => $this->images,
            'next_bot_dialog_command_id' => $this->next_bot_dialog_command_id,
            'bot_dialog_group_id' => $this->bot_dialog_group_id,
            'bot_dialog_group' => $this->whenLoaded("botDialogGroup"),
            'result_channel' => $this->result_channel,
            'result_flags' => $this->result_flags ?? [],
            'store_to' => $this->store_to,
        ];
    }

    protected function recursiveChain($commandId, &$refs)
    {
        $command = BotDialogCommand::query()
            ->where("id", $commandId)
            ->first();

        $refs = is_null($refs) ? [] : $refs;

        if (!in_array( $commandId , $refs))
            $refs[] = $commandId;

        if (!is_null($command->next_bot_dialog_command_id)) {
            $refs[] = $command->next_bot_dialog_command_id;
            $this->recursiveChain($command->next_bot_dialog_command_id, $refs);
        }

    }
}
