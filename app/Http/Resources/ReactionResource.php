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
        if (! auth('sanctum')->check()) {
            return '';
        }

        $like = LikeReactionEnum::LIKE->value;

        $dislike = LikeReactionEnum::DISLIKE->value;

        $user = auth('sanctum')->user();

        $reactantFacade = $this->viaLoveReactant();

        if ($reactantFacade->isReactedBy($user, $like)) {
            return $like;
        } elseif ($reactantFacade->isReactedBy($user, $dislike)) {
            return $dislike;
        } else {
            return '';
        }
    }

    private function getFavoriteState(): bool
    {
        if (! auth('sanctum')->check()) {
            return false;
        }

        $favorite = FavoriteReactionEnum::Favorite->value;

        $user = auth('sanctum')->user();

        $reactantFacade = $this->viaLoveReactant();

        if ($reactantFacade->isReactedBy($user, $favorite)) {
            return true;
        } else {
            return false;
        }
    }
}
