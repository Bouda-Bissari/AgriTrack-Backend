<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\AddLandRequest;
use App\Models\Land;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LandController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(AddLandRequest $request)
    {
        /**
         * je dois vérifier que l'utilisateur est connecté et qu'il a le rôle 'landOwner'
         * je decommenterai le code une fois que l'auth sera terminée avec les rôles
         */

        /*
        // Vérifier que l'utilisateur est connecté
        $user = Auth::user();

        if (!$user) {
            return response()->json(['message' => 'Utilisateur non connecté.'], 401);
        }

        // Vérifier si l'utilisateur a le rôle 'landOwner'
        if (!$user->hasRole('landOwner')) {
            return response()->json(['message' => 'Accès interdit. Vous devez être un propriétaire de terre.'], 403);
        }

        // Vérifier si l'utilisateur existe bien
        $userToCheck = User::find($request->user_id);
        if (!$userToCheck) {
            return response()->json(['message' => 'Utilisateur non trouvé.'], 404);
        }*/

        $data = $request->validated();

        // Stockage avec nom unique
        if ($request->hasFile('ownershipdoc')) {
            $originalName = $request->file('ownershipdoc')->getClientOriginalName();
            $filename = time() . '_' . uniqid() . '_' . $originalName;
            $path = $request->file('ownershipdoc')->storeAs('documents', $filename, 'public');
            $data['ownershipdoc'] = $path;
        }

        $parcelle = Land::create($data);

        return response()->json([
            'message' => 'Parcelle ajoutée avec succès.',
            'data' => $parcelle
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id)
    {
        $user = Auth::user();

        $land = Land::find($id);

        if (!$land) {
            return response()->json(['message' => 'Parcelle non trouvée.'], 404);
        }

        if ($land->user_id !== $user->id) {
            return response()->json(['message' => 'Accès interdit. Cette parcelle ne vous appartient pas.'], 403);
        }

        $land->delete();

        return response()->json(['message' => 'Parcelle supprimée avec succès.']);
    }
}
