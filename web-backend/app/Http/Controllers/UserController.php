<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        return response()->json(User::all(), 200);
    }

    // Create new user
    public function store(Request $request)
    {
        $validated = $request->validate([
            'userEmail' => 'required|email|unique:users,userEmail',
            'userName' => 'required|string|max:255',
            'userPassword' => 'required|string|min:6',
            'userImg' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if ($request->hasFile('userImg')) {
            $file = $request->file('userImg');
            $filename = time() . '_' . $file->getClientOriginalName();
            $filePath = 'uploads/user_images/';
            $file->move(public_path($filePath), $filename);
            $validated['userImg'] = $filePath . $filename;
        }

        $validated['userPassword'] = Hash::make($validated['userPassword']);
        $validated['isActivated'] = false;

        $user = User::create($validated);

        return response()->json($user, 201);
    }

    // Get single user
    public function show($id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        return response()->json($user);
    }

    // Update user
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $validated = $request->validate([
            'userEmail' => 'sometimes|email|unique:users,userEmail,' . $id . ',userId',
            'userName' => 'sometimes|string|max:255',
            'userPassword' => 'sometimes|string|min:6',
            'userImg' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'isActivated' => 'boolean',
        ]);

        if (isset($validated['userPassword'])) {
            $validated['userPassword'] = Hash::make($validated['userPassword']);
        }

        if ($request->hasFile('userImg')) {
            $file = $request->file('userImg');
            $filename = time() . '_' . $file->getClientOriginalName();
            $filePath = 'uploads/user_images/';
            $file->move(public_path($filePath), $filename);
            $validated['userImg'] = $filePath . $filename;
        }

        $user->update($validated);

        return response()->json($user);
    }

    // Delete user
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return response()->json(['message' => 'User deleted']);
    }
}
