<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LocationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'images' => $this->images,
            'lat' => $this->lat,
            'lon' => $this->lon,
            'address' => $this->address,
            'description' => $this->description,
            'location_channel' => $this->location_channel,
            'company_id' => $this->company_id,
            'is_active' => $this->is_active,
            'can_booking' => $this->can_booking,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'deleted_at' => $this->deleted_at,
            'imageMenus' => ImageMenuCollection::make($this->whenLoaded('imageMenus')),
            'company' => CompanyResource::make($this->whenLoaded('company')),
        ];
    }
}
