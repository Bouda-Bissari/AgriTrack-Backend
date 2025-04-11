<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller
{
    /**
     * Inscription d'un nouvel utilisateur.
     */
    public function register(RegisterRequest $request)
    {
        // Création de l'utilisateur
        $user = User::create([
            'firstName'     => $request->firstName,
            'lastName'      => $request->lastName,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'phoneNumber'   => $request->phoneNumber,
            'role' => $request->role,
        ]);

        // Création d'un token d'authentification
        $tokenResult = $user->createToken('token');
        $token = $tokenResult->plainTextToken;

        // Création de la réponse avec le token dans le cookie et le JSON
        $cookie = cookie('token', $token, 60 * 24); // cookie valable 1 jour

        return response()->json([
            'message' => 'Inscription réussie',
            'user'    => $user,
            'token'   => $token,
        ], 201)->withCookie($cookie);
    }

    /**
     * Connexion d'un utilisateur.
     */
    public function login(LoginRequest $request)
    {
        $request->validated();

        if (!Auth::attempt($request->only('email', 'password'))) {
            return response([
                'message' => 'Invalid credentials'
            ], Response::HTTP_UNAUTHORIZED);
        }

        $user = Auth::user();
        $token = $user->createToken('token')->plainTextToken;

        $cookie = cookie('jwt', $token, 60 * 24, '/', null, false, false, false);


        return response()->json([
            'user' => $user,
            'token' => $token
        ])->withCookie($cookie);
        
    }



    /**
     * Déconnexion de l'utilisateur authentifié.
     */
    // public function logout(Request $request)
    // {
    //     // Révocation de tous les tokens de l'utilisateur connecté
    //     $request->user()->tokens()->delete();

    //     // Suppression du cookie en le remplaçant par un cookie expiré
    //     $cookie = cookie('token', '', -1);

    //     return response()->json([
    //         'message' => "Déconnexion réussie."
    //     ], 200)->withCookie($cookie);
    // }


    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        $cookie = Cookie::forget('jwt');
        
        return response(['message' => 'Logged out'])->withCookie($cookie);
    }
}
