<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TransactionResource extends JsonResource
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
            'tgl_transaksi' => $this->tgl_transaksi->toDateString(),
            'deskripsi' => $this->deskripsi,
            'tipe' => $this->tipe,
            'nominal' => (float) $this->nominal,
            'formatted_nominal' => $this->formatted_nominal,
            'formatted_tgl_transaksi' => $this->formatted_tgl_transaksi,
            'category' => $this->category ? new CategoryResource($this->category) : null,
        ];
    }
}
