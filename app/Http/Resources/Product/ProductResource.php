<?php

namespace App\Http\Resources\Product;

use App\Http\Resources\Collection\CollectionResource;
use App\Http\Resources\Color\ColorResource;
use App\Http\Resources\Size\SizeResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
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
            'title' => $this->title,
            'description' => $this->description,
            'price' => number_format($this->price, 2),
            'images' => $this->images,
            'quantity' => $this->quantity,
            'sizes' => SizeResource::collection($this->whenLoaded('sizes')),
            'collections' => CollectionResource::collection($this->whenLoaded('collections')),
            'created_at' => $this->created_at?->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at?->format('Y-m-d H:i:s')
        ];
    }
}
