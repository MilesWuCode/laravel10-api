<?php

namespace App\Listeners;

use App\Enums\FavoriteReactionEnum;
use App\Events\FavoriteReactionEvent;
use App\Models\Post;
use Cog\Laravel\Love\Reaction\Events\ReactionHasBeenAdded;
use Cog\Laravel\Love\Reaction\Events\ReactionHasBeenRemoved;
use Cog\Laravel\Love\ReactionType\Models\ReactionType;
use Illuminate\Events\Dispatcher;

class ReactionEventSubscriber
{
    public function handleAdded(ReactionHasBeenAdded $event): void
    {
        $reaction = $event->getReaction();

        $type = $reaction->getType();

        $reactable = $reaction->getReactant()->getReactable();

        $user = $reaction->getReacter()->getReacterable();

        if ($type->isEqualTo(ReactionType::fromName(FavoriteReactionEnum::Favorite->value))) {

            if ($reactable::class === Post::class) {
                event(new FavoriteReactionEvent($user, 'post', $reactable->id, true));
            }

        }
    }

    public function handleRemoved(ReactionHasBeenRemoved $event): void
    {
        $reaction = $event->getReaction();

        $type = $reaction->getType();

        $model = $reaction->getReactant()->getReactable();

        $user = $reaction->getReacter()->getReacterable();

        if ($type->isEqualTo(ReactionType::fromName(FavoriteReactionEnum::Favorite->value))) {
            event(new FavoriteReactionEvent($user, $model, false));
        }
    }

    /**
     * Handle the event.
     */
    public function subscribe(Dispatcher $events): array
    {
        return [
            ReactionHasBeenAdded::class => 'handleAdded',
            ReactionHasBeenRemoved::class => 'handleRemoved',
        ];
    }
}
