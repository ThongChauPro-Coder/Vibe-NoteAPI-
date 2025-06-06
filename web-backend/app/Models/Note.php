<?php

namespace App\Models;
use App\Models\SharedNote;
use Illuminate\Database\Eloquent\Model;

class Note extends Model
{
    protected $primaryKey = 'noteId';
    protected $fillable = ['title', 'content'];

    public function getRouteKeyName()
    {
        return 'noteId';
    }

    public function labels()
    {
        return $this->belongsToMany(Label::class, 'note_link_label', 'noteId', 'labelId');
    }

    public function sharedNotes()
    {
        return $this->hasMany(SharedNote::class, 'note_id');
    }
}
