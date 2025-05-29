<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        try {
            // Validate the request data
            $credentials = $request->validate([
                'email'    => 'required|email',
                'password' => 'required|string',
            ]);

            // Attempt to authenticate the user
            if (! auth()->attempt($credentials)) {
                return response()->json(['message' => 'Invalid credentials'], 401);
            }

            $user = auth()->user();

                                       // Generate a new API token for the authenticated user
            $user->tokens()->delete(); // Optional: delete existing tokens
            $token = $user->createToken('API Token')->plainTextToken;

            // Return the token in the response
            return response()->json(['token' => $token], 200);
        } catch (\Throwable $e) {
            \Log::error("Login error: " . $e->getMessage());
            return response()->json(['message' => 'Internal Server Error'], 500);
        }
    }
}
