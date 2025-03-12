<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;

class AuthController extends Controller
{
    /**
     * Register a new user
     */
    public function register(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => [
                'required',
                'string',
                'confirmed',
                Password::min(8)
                    ->mixedCase()
                    ->numbers()
                    ->symbols()
            ],
        ]);

        if ($validator->fails()) {
            return ResponseHelper::validationError($validator->errors());
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        $data = [
            'user' => new UserResource($user),
            'access_token' => $token,
            'token_type' => 'Bearer',
        ];

        return ResponseHelper::created($data, 'User registered successfully');
    }

    /**
     * Log in a user
     */
    public function login(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ]);

        if ($validator->fails()) {
            return ResponseHelper::validationError($validator->errors());
        }

        // Attempt to authenticate
        if (!Auth::attempt($request->only('email', 'password'))) {
            return ResponseHelper::unauthorized('Invalid login credentials');
        }

        $user = User::where('email', $request->email)->firstOrFail();

        // For security, revoke all existing tokens before creating a new one
        $user->tokens()->delete();

        $token = $user->createToken('auth_token')->plainTextToken;

        $data = [
            'user' => new UserResource($user),
            'access_token' => $token,
            'token_type' => 'Bearer',
        ];

        return ResponseHelper::success($data, 'User logged in successfully');
    }

    /**
     * Get authenticated user
     */
    public function user(Request $request): JsonResponse
    {
        // Get the authenticated user
        $user = $request->user();

        // Use our ResponseHelper to ensure the response has the expected format
        return response()->json([
            'success' => true,
            'data' => new UserResource($user)
        ]);
    }

    /**
     * Log out a user
     */
    public function logout(Request $request): JsonResponse
    {
        // Delete the current token that was used for the request
        $request->user()->currentAccessToken()->delete();

        return ResponseHelper::success(null, 'User logged out successfully');
    }
}