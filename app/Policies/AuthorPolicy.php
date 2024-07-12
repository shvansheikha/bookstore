<?php

namespace App\Policies;

use App\Models\Author;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class AuthorPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Author $author): bool
    {
        return $user->id === $author->user_id;
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, Author $author): bool
    {
        return $user->id === $author->user_id;
    }

    public function delete(User $user, Author $author): bool
    {
        return $user->id === $author->user_id;
    }
}
