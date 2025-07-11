<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BalanceResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'initial_balance' => $this->initial_balance,
            'current_balance' => $this->current_balance,
            'formatted_initial_balance' => $this->formatted_initial_balance,
            'formatted_current_balance' => $this->formatted_current_balance,
            'last_updated' => $this->last_updated->format('Y-m-d H:i:s'),
            'last_updated_human' => $this->last_updated->diffForHumans(),
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at->format('Y-m-d H:i:s'),
        ];
    }
}
