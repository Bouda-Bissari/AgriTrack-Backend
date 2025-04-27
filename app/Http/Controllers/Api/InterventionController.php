<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\AddInterventionRequest;
use App\Http\Requests\UpdateInterventionRequest;
use App\Models\Intervention;
use App\Models\Land;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InterventionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index() //Affiche la liste des interventions à faire aux travailleurs
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json(['message' => 'Utilisateur non connecté.'], 401);
        }

        if ($user->role !== 'worker') {
            return response()->json(['message' => 'Accès interdit. Seuls les travailleurs peuvent voir les interventions à faire.'], 403);
        }

        $interventions = Intervention::where('isDone', false)->get();

        if ($interventions->isEmpty()) {
            return response()->json(['message' => 'Aucune intervention en attente trouvée.'], 404);
        }

        return response()->json([
            'message' => 'Liste des interventions à faire récupérée avec succès.',
            'data' => $interventions
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(AddInterventionRequest $request)
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json(['message' => 'Utilisateur non connecté.'], 401);
        }

        if ($user->role !== 'landOwner') {
            return response()->json(['message' => 'Accès interdit. Seul un proprietaire de terrain peut créer une intervention.'], 403);
        }

        // Vérifie que le terrain appartient bien au landOwner
        $land = Land::where('id', $request->land_id)
            ->where('user_id', $user->id)
            ->first();

        if (!$land) {
            return response()->json(['message' => 'Ce terrain ne vous appartient pas.'], 403);
        }

        $data = $request->validated();
        $data['isDone'] = $data['isDone'] ?? false; // Valeur par défaut

        $intervention = Intervention::create($data);

        return response()->json([
            'message' => 'Intervention créée avec succès.',
            'data' => $intervention
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id)
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json(['message' => 'Utilisateur non connecté.'], 401);
        }

        $intervention = Intervention::with('land')->find($id);

        if (!$intervention) {
            return response()->json(['message' => 'Intervention non trouvée.'], 404);
        }

        if ($user->role === 'landOwner') {
            // Vérifie que l'intervention appartient bien au landOwner
            if ($intervention->land->user_id !== $user->id) {
                return response()->json(['message' => 'Accès interdit.'], 403);
            }
        } elseif ($user->role === 'worker') {
            // les workers ne voient que les interventions non terminées
            if ($intervention->isDone) {
                return response()->json(['message' => 'Intervention déjà terminée.'], 403);
            }
        } else {
            return response()->json(['message' => 'Accès interdit.'], 403);
        }

        return response()->json([
            'message' => 'Intervention récupérée avec succès.',
            'data' => $intervention
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateInterventionRequest $request, int $id)
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json(['message' => 'Utilisateur non connecté.'], 401);
        }

        if ($user->role !== 'landOwner') {
            return response()->json(['message' => 'Accès interdit. Seul un propriétaire peut modifier une intervention.'], 403);
        }

        $intervention = Intervention::find($id);

        if (!$intervention) {
            return response()->json(['message' => 'Intervention non trouvée.'], 404);
        }

        if ($intervention->land->user_id !== $user->id) {
            return response()->json(['message' => 'Vous ne pouvez modifier que vos propres interventions.'], 403);
        }

        if ($intervention->isDone) {
            return response()->json(['message' => 'Impossible de modifier une intervention déjà terminée.'], 403);
        }

        $validated = $request->validated();

        $intervention->update($validated);

        return response()->json([
            'message' => 'Intervention modifiée avec succès.',
            'data' => $intervention
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id)
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json(['message' => 'Utilisateur non connecté.'], 401);
        }

        if ($user->role !== 'landOwner') {
            return response()->json(['message' => 'Accès interdit. Seul un propriétaire peut supprimer une intervention.'], 403);
        }

        $intervention = Intervention::find($id);

        if (!$intervention) {
            return response()->json(['message' => 'Intervention non trouvée.'], 404);
        }

        if ($intervention->land->user_id !== $user->id) {
            return response()->json(['message' => 'Vous ne pouvez supprimer que vos propres interventions.'], 403);
        }

        $intervention->delete();

        return response()->json(['message' => 'Intervention supprimée avec succès.'], 200);
    }

    /**
     * Récupérer toutes les interventions créées par l'utilisateur connecté.
     */
    public function getInterventionsByUser()
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json(['message' => 'Utilisateur non connecté.'], 401);
        }

        if ($user->role !== 'landOwner') {
            return response()->json(['message' => 'Accès interdit. Seul un proprietaire de terrain peut voir ses interventions.'], 403);
        }

        // Récupérer toutes les interventions où les terrains appartiennent à cet utilisateur
        $interventions = Intervention::whereHas('land', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })->get();

        if ($interventions->isEmpty()) {
            return response()->json(['message' => 'Aucune intervention trouvée pour cet utilisateur.'], 404);
        }

        return response()->json([
            'message' => 'Liste des interventions récupérées avec succès.',
            'data' => $interventions
        ], 200);
    }
}
