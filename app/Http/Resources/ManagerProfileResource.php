<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ManagerProfileResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'bot_user_id' => $this->bot_user_id,
            'image' => $this->image ?? null,
            'info' => $this->info,
            'referral' => $this->referral,
            'strengths' => $this->strengths,
            'weaknesses' => $this->weaknesses,
            'educations' => $this->educations,
            'social_links' => $this->social_links,
            'skills' => $this->skills,
            'scripts' => $this->scripts??[],
            'stable_personal_discount' => $this->stable_personal_discount ?? 0,
            'permanent_personal_discount' => $this->permanent_personal_discount ?? 0,
            'max_company_slot_count' => $this->max_company_slot_count ?? 0,
            'max_bot_slot_count' => $this->max_bot_slot_count ?? 0,
            'balance' => $this->balance ?? 0,
            'verified_at' => $this->verified_at,
        ];
    }
}
