<?php

namespace App\Http\Controllers;

use App\Models\SharedNote;
use App\Models\SharedNoteRecipient;
use App\Models\Note;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class NoteShareController extends Controller
{
    use AuthorizesRequests;

    public function share(Request $request, Note $note)
    {
        $this->authorize('update', $note); 

        $validated = $request->validate([
            'recipients' => 'required|array|min:1',
            'recipients.*.userEmail' => 'required|email|exists:users,userEmail',
            'recipients.*.permission' => 'required|in:read-only,editable',
        ]);

        $ownerId = Auth::id();

        $sharedNote = SharedNote::create([
            'owner_id' => $ownerId,
            'note_id' => $note->noteId,
        ]);

        foreach ($validated['recipients'] as $recipientData) {
            $recipient = \App\Models\User::where('userEmail', $recipientData['userEmail'])->first();

            SharedNoteRecipient::create([
                'shared_note_id' => $sharedNote->id,
                'recipient_id' => $recipient->userId,
                'permission' => $recipientData['permission'],
            ]);
        }

        return response()->json(['message' => 'Note shared successfully']);
    }

    public function recipients(Note $note)
    {
        $this->authorize('view', $note);

        $shared = $note->sharedNotes()->with(['recipients.recipient'])->get();

        return response()->json($shared);
    }

    public function updateRecipient(Request $request, $recipientId)
    {
        $recipient = SharedNoteRecipient::findOrFail($recipientId);
        $this->authorize('update', $recipient->sharedNote->note);

        $request->validate([
            'permission' => 'required|in:read-only,editable',
        ]);

        $recipient->update(['permission' => $request->permission]);

        return response()->json(['message' => 'Permission updated successfully']);
    }

    public function revokeRecipient($recipientId)
    {
        $recipient = SharedNoteRecipient::findOrFail($recipientId);
        $this->authorize('update', $recipient->sharedNote->note);

        $recipient->delete();

        return response()->json(['message' => 'Sharing access revoked successfully']);
    }
}
