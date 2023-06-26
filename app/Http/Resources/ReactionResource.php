<?php

namespace App\Http\Resources;

use App\Enums\FavoriteReactionEnum;
use App\Enums\LikeReactionEnum;
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
            'like_state' => $this->getLikeState(),
            'favorite_state' => $this->getFavoriteState(),
        ];
    }

    private function getLikeState(): string
    {
        // list n+1: ->with(['loveReactant.reactions'])

        $like = LikeReactionEnum::LIKE->value;

        $dislike = LikeReactionEnum::DISLIKE->value;

        if (auth()->check() && $this->viaLoveReactant()->isReactedBy(auth()->user(), $like)) {
            return $like;
        } elseif (auth()->check() && $this->viaLoveReactant()->isReactedBy(auth()->user(), $dislike)) {
            return $dislike;
        } else {
            return '';
        }
    }

    private function getFavoriteState(): bool
    {
        // list n+1: ->with(['loveReactant.reactions'])

        $favorite = FavoriteReactionEnum::Favorite->value;

        if (auth()->check() && $this->viaLoveReactant()->isReactedBy(auth()->user(), $favorite)) {
            return true;
        } else {
            return false;
        }
    }
}
