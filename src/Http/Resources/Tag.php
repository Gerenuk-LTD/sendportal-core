<?php

namespace Sendportal\Base\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Sendportal\Base\Http\Resources\Subscriber as SubscriberResource;

class Tag extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'subscribers' => SubscriberResource::collection($this->whenLoaded('subscribers')),
            'created_at' => $this->created_at->toDateTimeString(),
            'update_at' => $this->updated_at->toDateTimeString()
        ];
    }
}
