<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SharedNote extends Model
{
    protected $fillable = ['owner_id', 'note_id', 'shared_at'];

    public function recipients()
    {
        return $this->hasMany(SharedNoteRecipient::class, 'shared_note_id');
    }

    public function note()
    {
        return $this->belongsTo(Note::class, 'note_id');
    }

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }
}
