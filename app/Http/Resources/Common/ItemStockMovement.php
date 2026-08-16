<?php

namespace App\Http\Resources\Common;

use Illuminate\Http\Resources\Json\JsonResource;

class ItemStockMovement extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'company_id' => $this->company_id,
            'item_id' => $this->item_id,
            'via_item_id' => $this->via_item_id,
            'via_item_name' => $this->via_item_id ? $this->via_item->name : null,
            'document_id' => $this->document_id,
            'document_item_id' => $this->document_item_id,
            'type' => $this->type,
            // PostgreSQL returns decimals as strings
            'quantity' => (float) $this->quantity,
            'moved_at' => $this->moved_at ? $this->moved_at->toIso8601String() : '',
            'description' => $this->description,
            'created_from' => $this->created_from,
            'created_by' => $this->created_by,
            'created_at' => $this->created_at ? $this->created_at->toIso8601String() : '',
            'updated_at' => $this->updated_at ? $this->updated_at->toIso8601String() : '',
        ];
    }
}
