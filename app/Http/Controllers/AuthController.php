<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    // app/Http/Controllers/AuthController.php

    public function register(Request $request)
    {
        \Log::info('Register endpoint hit');
    
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'phone_number' => 'required|string|unique:customers',
            'email' => 'nullable|string|email|max:255|unique:customers',
        ]);
    
        $customer = Customer::create([
            'name' => $validatedData['name'],
            'phone_number' => $validatedData['phone_number'],
            'email' => $validatedData['email'],
            'points' => 0, // Default nilai 0
        ]);
    
        $token = $customer->createToken('authToken')->plainTextToken;
    
        return response()->json([
            'name' => $customer->name,
            'phone_number' => $customer->phone_number,
            'email' => $customer->email,
            'points' => $customer->points,
            'token' => $token,

        ], 201);
    }
    
    public function login(Request $request)
{
    // Validate the phone number input
    $request->validate([
        'phone_number' => 'required|string',
    ]);

    // Attempt to find the user by phone number
    $user = Customer::where('phone_number', $request->phone_number)->first();

    // If user is not found, throw a validation exception
    if (!$user) {
        throw ValidationException::withMessages([
            'phone_number' => ['The provided credentials are incorrect.'],
        ]);
    }

    // Update user's logged in status and create a token
    $user->update(['is_logged_in' => true]);
    $token = $user->createToken('auth_token')->plainTextToken;

    // Return the token and customer_id in the response
    return response()->json(['token' => $token, 'customer_id' => $user->id], 200);
}

    

    public function logout(Request $request)
    {
        $user = $request->user();
        $user->update(['is_logged_in' => false]);
        $user->currentAccessToken()->delete();

        return response()->json(['message' => 'Successfully logged out'], 200);
    }
}
