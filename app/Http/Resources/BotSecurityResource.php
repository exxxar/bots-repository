<?php

namespace App\Http\Resources;

use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BotSecurityResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'bot_domain' => $this->bot_domain,
            'welcome_message' => $this->welcome_message,
            'image' => $this->image,
            'description' => $this->description,
            'info_link' => $this->info_link,
            'social_links' => $this->social_links,
            'company' => new CompanySecurityResource($this->whenLoaded('company')),
            'imageMenus' => ImageMenuResource::collection($this->whenLoaded('imageMenus')),
            //'productCategories' => ProductCategoryCollection::make($this->whenLoaded('productCategories')),
        ];
    }
}
