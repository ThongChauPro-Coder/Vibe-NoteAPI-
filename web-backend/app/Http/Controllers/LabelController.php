<?php

namespace App\Http\Controllers;
use App\Models\Label;
use Illuminate\Http\Request;

class LabelController extends Controller
{
    public function index()
    {
        return response()->json(Label::all());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'labelName' => 'required|string|max:255|unique:labels',
            'labelContent' => 'nullable|string',
        ]);

        $label = Label::create($validated);
        return response()->json($label, 201);
    }

    public function show($id)
    {
        $label = Label::findOrFail($id);
        return response()->json($label);
    }

    public function update(Request $request, $id)
    {
        $label = Label::findOrFail($id);

        $validated = $request->validate([
            'labelName' => 'required|string|max:255|unique:labels,labelName,' . $id . ',labelId',
            'labelContent' => 'nullable|string',
        ]);

        $label->update($validated);
        return response()->json($label);
    }

    public function destroy($id)
    {
        $label = Label::findOrFail($id);
        $label->delete();

        return response()->json(['message' => 'Label deleted']);
    }
}
