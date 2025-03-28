<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Enums\UserTokenAbilities;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\AuthRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{

    public function __invoke(AuthRequest $request): \Illuminate\Http\JsonResponse
    {
        /**
         * @var User $user
         */
         $user = User::query()->where('email',$request->email)->first();

         if(is_null($user)){
             return response()->json([
                 'message' => "Authentication failed! User doesnt exists!"
             ],401);
         }

         if(!Hash::check($request->password,$user->password)){
             return response()->json([
                 'message' => "Authentication failed! Password doesn't match!"
             ],401);
         }

         $token = $user->createToken($user->email,[UserTokenAbilities::ALL],now()->addDays(2));

         return response()->json([
             'token' => $token->plainTextToken
         ],201);
    }


    public function logout(): \Illuminate\Http\JsonResponse
    {
        /**
         * @var User $user
         */
        $user = \auth()->user();
        $user->tokens()->delete();

        return response()->json([
            'redirect' => '/'
        ],200);
    }
}
