<?php

namespace App\Http\Controllers;

use App\Models\Note;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;

class NoteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $notes = Note::orderByDesc('isPinned')
                    ->orderByDesc('pinnedAt')
                    ->orderByDesc('updated_at')
                    ->get();

        return response()->json($notes);
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'noteTitle' => 'required|string|max:255',
            'noteContent' => 'nullable|string',
        ]);
        
        $note = Note::create($validated);
        return response()->json($note, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $note = Note::find($id);

        if(!$note){
            return response()->json(['message' => 'Note not found'], 404);
        }

        return response()->json($note, 200);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Note $note)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $note = Note::find($id);

        if(!$note){
            return response()->json(['message' => 'Note not found'], 404);
        }

        $validated = $request->validate([
            'noteTitle' => 'required|string|max:255',
            'noteContent' => 'nullable|string',
        ]);
        
        $note->update($validated);        
        return response()->json($note, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $note = Note::find($id);

        if(!$note){
            return response()->json(['message' => 'Note not found'], 404);
        }

        $note->delete();
        return response()->json(['message' => 'Note deleted successfully'], 200);
    }

    public function pin($id)
    {
        $note = Note::findOrFail($id);
        $note->isPinned = true;
        $note->pinnedAt = now();
        $note->save();

        return response()->json(['message' => 'Note pinned successfully.']);
    }

    public function unpin($id)
    {
        $note = Note::findOrFail($id);
        $note->isPinned = false;
        $note->pinnedAt = null;
        $note->save();

        return response()->json(['message' => 'Note unpinned.']);
    }

    public function setPassword(Request $request, $id)
    {
        $request->validate([
            'password' => 'required|min:6|confirmed',
        ]);

        $note = Note::findOrFail($id);
        $note->notePassword = bcrypt($request->password);
        $note->save();

        return response()->json(['message' => 'Password set successfully']);
    }

    public function unlock(Request $request, $id)
    {
        $request->validate(['password' => 'required']);

        $note = Note::findOrFail($id);

        if (!$note->notePassword || !Hash::check($request->password, $note->notePassword)) {
            return response()->json(['message' => 'Incorrect password'], 403);
        }

        return response()->json(['note' => $note]);
    }

    public function disablePassword(Request $request, $id)
    {
        $request->validate(['password' => 'required']);

        $note = Note::findOrFail($id);

        if (!Hash::check($request->password, $note->notePassword)) {
            return response()->json(['message' => 'Incorrect password'], 403);
        }

        $note->notePassword = null;
        $note->save();

        return response()->json(['message' => 'Password protection disabled']);
    }

    public function changePassword(Request $request, $id)
    {
        $request->validate([
            'old_password' => 'required',
            'new_password' => 'required|min:6|confirmed',
        ]);

        $note = Note::findOrFail($id);

        if (!$note->notePassword) {
            return response()->json(['message' => 'This note does not have password protection enabled.'], 400);
        }

        if (!Hash::check($request->old_password, $note->notePassword)) {
            return response()->json(['message' => 'Current password is incorrect.'], 403);
        }

        $note->notePassword = bcrypt($request->new_password);
        $note->save();

        return response()->json(['message' => 'Password updated successfully.']);
    }

    // Thêm vào cuối class NoteController

    public function autoSave(Request $request, $id)
    {
        $note = Note::find($id);

        if (!$note) {
            return response()->json(['message' => 'Note not found'], 404);
        }

        $validated = $request->validate([
            'noteTitle' => 'nullable|string|max:255',
            'noteContent' => 'nullable|string',
        ]);
        
        if (isset($validated['noteTitle'])) {
            $note->noteTitle = $validated['noteTitle'];
        }
        
        if (isset($validated['noteContent'])) {
            $note->noteContent = $validated['noteContent'];
        }
        
        $note->save();
        
        return response()->json(['message' => 'Note auto saved successfully']);
    }
}
