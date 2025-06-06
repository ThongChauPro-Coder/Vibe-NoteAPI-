<?php

namespace App\Http\Controllers;
use App\Models\Note;
use App\Models\Label;

class NoteLabelController extends Controller
{
    public function attach($noteId, $labelId)
    {
        $note = Note::findOrFail($noteId);
        $note->labels()->attach($labelId);

        return response()->json(['message' => 'Label attached']);
    }

    public function detach($noteId, $labelId)
    {
        $note = Note::findOrFail($noteId);
        $note->labels()->detach($labelId);

        return response()->json(['message' => 'Label detached']);
    }
}
