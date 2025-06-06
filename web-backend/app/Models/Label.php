<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Label extends Model
{
    public function notes()
    {
        return $this->belongsToMany(Note::class, 'note_link_label', 'labelId', 'noteId');
    }
}
