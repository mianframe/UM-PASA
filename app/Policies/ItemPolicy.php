<?php

namespace App\Policies;

use App\Models\Item;
use App\Models\User;

class ItemPolicy
{
    public function view(?User $user, Item $item): bool
    {
        if ($item->moderation_status === Item::MODERATION_APPROVED) {
            return true;
        }

        return $user !== null && ($user->id === $item->user_id || $user->isAdmin());
    }

    public function request(User $user, Item $item): bool
    {
        return $item->isAvailable() && $user->id !== $item->user_id;
    }

    /**
     * Determine if the user can update the item.
     */
    public function update(User $user, Item $item): bool
    {
        return $user->id === $item->user_id || $user->isAdmin();
    }

    /**
     * Determine if the user can delete the item.
     */
    public function delete(User $user, Item $item): bool
    {
        return $user->id === $item->user_id || $user->isAdmin();
    }
}
