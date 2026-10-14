<?php

namespace App\Http\Controllers\Api;

use App\Models\ApiToken;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class ApiAuthController
{
    public function login(Request $request)
    {
        $data = $request->validate(['email' => ['required', 'email'], 'password' => ['required', 'string']]);
        $user = User::where('email', $data['email'])->where('is_admin', true)->where('is_active', true)->first();

        if (! $user || ! Hash::check($data['password'], $user->password)) {
            return response()->json(['message' => 'The provided credentials are incorrect.'], 422);
        }

        $plainToken = Str::random(40);
        ApiToken::create(['user_id' => $user->id, 'name' => $request->string('device_name', 'api-client'), 'token' => hash('sha256', $plainToken)]);
        return response()->json(['token' => $plainToken, 'user' => $user]);
    }

    public function me(Request $request)
    {
        return response()->json(['user' => $request->user()]);
    }

    public function logout(Request $request)
    {
        $request->attributes->get('api_token')?->delete();
        return response()->json(['message' => 'Signed out.']);
    }
}
