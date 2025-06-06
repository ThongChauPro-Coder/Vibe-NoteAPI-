<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Mail\VerifyEmail;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response() -> json(User::latest() -> get());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function register(Request $request)
    {
        $fields = $request->validate([
            'userName' => 'required|string',
            'userEmail' => 'required|string|unique:users,userEmail',
            'userPassword' => 'required|string'
        ]);

        $user = User::create([
            'userName' => $fields['userName'],
            'userEmail' => $fields['userEmail'],
            'userPassword' => bcrypt($fields['userPassword']),
        ]);

        Mail::to($fields['userEmail'])->send(new VerifyEmail($user));

        $token = $user -> createToken('webbackendapp')->plainTextToken;

        $response = [
            'user' => $user,
            'token' => $token
        ];

        return response($response, 201);
    }

    public function login(Request $request)
    {
        $fields = $request->validate([
            'userEmail' => 'required|string|email',
            'userPassword' => 'required|string'
        ]);

        $user = User::where('userEmail', $request->userEmail)->first();

        if(!$user || !Hash::check($fields['userPassword'], $user->userPassword)){
            return response([
                'message' => 'Bad credentials'
            ], 401);
        }

        $token = $user -> createToken('webbackendapp')->plainTextToken;

        $response = [
            'user' => $user,
            'token' => $token
        ];

        return response($response, 201);
    }

    public function logout(Request $request)
    {   
        $request->user()->tokens()->delete();

        return response()->json([
            'message' => 'Logged out'
        ]);
    }

    public function changePassword(Request $request)
    {
        $request->validate([
            'old_password' => ['required'],
            'new_password' => ['required', 'min:6', 'confirmed'],
        ]);

        /** @var User $user */
        $user = Auth::user();

        if (!Hash::check($request->old_password, $user->userPassword)) {
            return response()->json(['message' => 'Old password is incorrect'], 422);
        }

        $user->userPassword = Hash::make($request->new_password);
        $user->save();

        return response()->json(['message' => 'Password updated successfully']);
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
