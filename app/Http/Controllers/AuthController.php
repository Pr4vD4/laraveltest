<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function auth(Request $request){
        $validator = Validator::make($request->all(),
            [
                'email' => 'required',
                'password' => 'required',
            ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ]);
        }
        $user = User::query()
            ->where('email', $request->email)
            ->first();

        if (Hash::check($request->password, $user->password)){
            Auth::login($user);
            $token = Auth::user()->createToken('login');
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'Логин иили пароль не верные',
            ], 403);
        }
        return response()->json([
            'token' => $token->plainTextToken
        ]);
    }


    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */

    public function registration(Request $request){

        $validator = Validator::make($request->all(),
        [
            'name' => 'required|string',
            'surname' => 'required|string',
            'email' => 'required|unique:users',
            'password' => 'required|confirmed',
        ],
        [
            'password.confirmed' => 'Пароль не совпадает',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ]);
        }
        $user = new User();
        $user->name = $request->name;
        $user->surname = $request->surname;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->save();
        return response()->json($user, 201);
    }
}
