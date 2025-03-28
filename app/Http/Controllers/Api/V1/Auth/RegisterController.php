<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Enums\UserTokenAbilities;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\AuthRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class RegisterController extends Controller
{

    public function __invoke(AuthRequest $request): JsonResponse
    {
        $user = new User();
        $user->name = $request->first_name . ' ' . $request->last_name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->save();

        $token = $user->createToken($user->email,[UserTokenAbilities::ALL],now()->addDays(2));

        return response()->json([
            'token' => $token->plainTextToken
        ],201);
    }
}
