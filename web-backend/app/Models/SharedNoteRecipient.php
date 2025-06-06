<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SharedNoteRecipient extends Model
{
    protected $fillable = ['shared_note_id', 'recipient_id', 'permission'];

    public function sharedNote()
    {
        return $this->belongsTo(SharedNote::class, 'shared_note_id');
    }

    public function recipient()
    {
        return $this->belongsTo(User::class, 'recipient_id');
    }
}
