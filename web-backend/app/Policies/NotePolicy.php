<?php

namespace App\Policies;

use App\Models\Note;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class NotePolicy
{
    public function update(User $user, Note $note): bool
    {
        return $user->id === $note->user_id;
    }

    public function view(User $user, Note $note): bool
    {
        return $user->id === $note->user_id;
    }

    public function delete(User $user, Note $note): bool
    {
        return $user->id === $note->user_id;
    }

    public function share(User $user, Note $note): bool
    {
        return $user->id === $note->user_id;
    }

}
