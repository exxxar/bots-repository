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

        $step = 0;

        $this->recursiveChain($this->id, $chain, $step);

        return [
            'id' => $this->id,
            'slug' => $this->slug,
            'pre_text' => $this->pre_text,
            'post_text' => $this->post_text,
            'error_text' => $this->error_text,
            'is_empty' => $this->is_empty ?? false,
            'is_inform' => $this->is_inform ?? false,
            'bot_id' => $this->bot_id,
            'chain' => $chain,
            'custom_stored_value' => $this->custom_stored_value ?? null,
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
            'use_result_as' => $this->use_result_as ?? null,
            'store_to' => $this->store_to,
            'answers' => $this->answers ?? [],
            'rules' => $this->rules ?? [],
        ];
    }

    protected function recursiveChain($commandId, &$refs, &$step)
    {
        $command = BotDialogCommand::query()
            ->where("id", $commandId)
            ->first();

        if (is_null($command))
            return;

        $refs = is_null($refs) ? [] : $refs;

        if (!in_array($commandId, $refs))
            $refs[] = $commandId;

        if (count(array_unique($refs)) != count($refs)){
            $refs = ["Ошибка связи цепочки (на шаге $step)"];
            return;
        }


        $step++;

        if (!is_null($command->next_bot_dialog_command_id)) {
            $refs[] = $command->next_bot_dialog_command_id;
            $this->recursiveChain($command->next_bot_dialog_command_id, $refs, $step);
        }

    }
}
