<?php

namespace App\Http\Controllers\Auth;
use App\Models\Bank;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        // Validate the request data
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        // Attempt to authenticate the user
        if (!auth()->attempt($credentials)) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        $user = auth()->user();

        // Generate a new API token for the authenticated user
        $user->tokens()->delete(); // Optional: delete existing tokens
        $token = $user->createToken('API Token')->plainTextToken;

        // Return the token in the response
        return response()->json(['token' => $token], 200);
    }

    public function bank_details(Request $request)
    {
        $user = auth()->user();

        $bank = Bank::find($request->bank_id);

    if (!$bank) {
        return response()->json(['message' => 'Bank not found'], 404);
    }

    return response()->json([
        'user_id' => $user->id,
        'bank_id' => $bank->id,
        'name' => $bank->name,
        'address' => $bank->address,
        'contact_person' => $bank->contact_person,
        'license_expiry_date' => $bank->license_expiry_date,
        'support_person' => $bank->support_person,
    ], 200);
    }
}
