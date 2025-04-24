<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\AddInterventionRequest;
use App\Models\Intervention;
use App\Models\Land;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InterventionController extends Controller
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
    public function destroy(string $id)
    {
        //
    }
}
