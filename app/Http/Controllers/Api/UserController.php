<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Get the authenticated user
     */
    public function getCurrentUser(Request $request)
    {
        return response()->json([
            'user' => $request->user()
        ], 200);
    }

    /**
     * Get a user by ID
     */
    public function getUser($id)
    {
        $user = User::find($id);
        
        if (!$user) {
            return response()->json([
                'message' => 'Utilisateur non trouvé'
            ], 404);
        }
        
        return response()->json([
            'user' => $user
        ], 200);
    }

    /**
     * Update user profile
     */
    public function updateProfile(Request $request)
    {
        $user = $request->user();
        
        $validator = Validator::make($request->all(), [
            'firstName' => 'sometimes|string|max:255',
            'lastName' => 'sometimes|string|max:255',
            'email' => [
                'sometimes',
                'email',
                Rule::unique('users')->ignore($user->id)
            ],
            'phoneNumber' => [
                'sometimes',
                'string',
                Rule::unique('users')->ignore($user->id)
            ],
            'bio' => 'sometimes|nullable|string',
            'profilImage' => 'sometimes|nullable|file', 
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'message' => 'Données invalides',
                'errors' => $validator->errors()
            ], 422);
        }
        
        // Mettre à jour les champs textuels
        $user->fill($request->only([
            'firstName',
            'lastName',
            'email',
            'phoneNumber',
            'bio',
        ]));
        
        // Gérer le téléchargement de l'image de profil
        if ($request->hasFile('profilImage')) {
            // Supprimer l'ancienne image si elle existe
            if ($user->profilImage) {
                Storage::disk('public')->delete($user->profilImage);
            }
            
            // Stocker la nouvelle image
            $path = $request->file('profilImage')->store('profile_images', 'public');
            $user->profilImage = $path;
        }
        
        $user->save();
        
        return response()->json([
            'message' => 'Profil mis à jour avec succès',
            'user' => $user
        ], 200);
    }

    /**
     * Update user password
     */
    public function updatePassword(Request $request)
    {
        $user = $request->user();
        
        $validator = Validator::make($request->all(), [
            'current_password' => 'required|string',
            'new_password' => 'required|string|min:8|confirmed',
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'message' => 'Données invalides',
                'errors' => $validator->errors()
            ], 422);
        }
        
        // Check if current password is correct
        if (!Hash::check($request->current_password, $user->password)) {
            return response()->json([
                'message' => 'Le mot de passe actuel est incorrect'
            ], 401);
        }
        
        // Update password
        $user->password = Hash::make($request->new_password);
        $user->save();
        
        return response()->json([
            'message' => 'Mot de passe mis à jour avec succès'
        ], 200);
    }
    
    /**
     * Block/unblock user (admin only)
     */
    public function toggleBlockUser(Request $request, $id)
    {
        // Check if authenticated user is admin
        if ($request->user()->role !== 'admin') {
            return response()->json([
                'message' => 'Action non autorisée'
            ], 403);
        }
        
        $user = User::find($id);
        
        if (!$user) {
            return response()->json([
                'message' => 'Utilisateur non trouvé'
            ], 404);
        }
        
        $user->is_blocked = !$user->is_blocked;
        $user->save();
        
        $status = $user->is_blocked ? 'bloqué' : 'débloqué';
        
        return response()->json([
            'message' => "L'utilisateur a été $status avec succès",
            'user' => $user
        ], 200);
    }
    
    /**
     * List all users (admin only)
     */
    public function listUsers(Request $request)
    {
        // Check if authenticated user is admin
        if ($request->user()->role !== 'admin') {
            return response()->json([
                'message' => 'Action non autorisée'
            ], 403);
        }
        
        $users = User::all();
        
        return response()->json([
            'users' => $users
        ], 200);
    }
}