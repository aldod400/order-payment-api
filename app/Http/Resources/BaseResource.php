<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Resources\Json\JsonResource;

class BaseResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        if ($this->resource instanceof AnonymousResourceCollection) {
            $response = $this->resource->toResponse($request)->getData(true);

            if (isset($response['meta'])) {
                $meta = $response['meta'];
                $links = $response['links'] ?? [];

                return [
                    'data' => $response['data'],
                    'current_page' => $meta['current_page'],
                    'from' => $meta['from'],
                    'last_page' => $meta['last_page'],
                    'per_page' => $meta['per_page'],
                    'to' => $meta['to'],
                    'total' => $meta['total'],
                    'links' => [
                        'first' => $links['first'] ?? null,
                        'last' => $links['last'] ?? null,
                        'prev' => $links['prev'] ?? null,
                        'next' => $links['next'] ?? null,
                    ],
                ];
            }

            return $response['data'] ?? $response;
        }

        if ($this->resource instanceof JsonResource) {
            return $this->resource->toArray($request);
        }

        // Default case
        return parent::toArray($request);
    }

    /**
     * Create a new instance with flexible parameter support
     *
     * @param  mixed  ...$parameters
     * @return static
     */
    public static function make(...$parameters)
    {
        return new static(...$parameters);
    }
}
