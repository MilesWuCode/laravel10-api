<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ReactionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // return parent::toArray($request);

        $reactantFacade = $this->viaLoveReactant();

        return [
            'like_count' => $reactantFacade->getReactionCounterOfType('like')->getCount(),
            'dislike_count' => $reactantFacade->getReactionCounterOfType('dislike')->getCount(),
            'like_state' => $this->like_state,
        ];
    }
}
