<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\AddLandRequest;
use App\Http\Requests\UpdateLandRequest;
use App\Models\Land;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class LandController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
    
        if (!$user) {
            return response()->json(['message' => 'Utilisateur non connecté.'], 401);
        }
    
        $query = Land::where('user_id', $user->id);
    
        // Filtres dynamiques
        if ($request->filled('name')) {
            $query->where('name', 'like', '%' . $request->input('name') . '%');
        }
    
        if ($request->filled('culture_type')) {
            $query->where('cultureType', $request->input('culture_type'));
        }
    
        if ($request->filled('land_status')) {
            $query->where('statut', $request->input('land_status'));
        }
    
        if ($request->filled('city')) {
            $query->where('city', 'like', '%' . $request->input('city') . '%');
        }

        $sortBy = $request->input('sort_by', 'created'); 
    if ($sortBy === 'updated') {
        $query->latest('updated_at');
    } else {
        $query->latest('created_at');
    }
    
        $lands = $query->paginate(10);
    
        return response()->json([
            'message' => 'Parcelles de l\'utilisateur récupérées avec succès.',
            'data' => $lands->items(),
            'total' => $lands->total(),
            'current_page' => $lands->currentPage(),
            'last_page' => $lands->lastPage(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(AddLandRequest $request)
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json(['message' => 'Utilisateur non connecté.'], 401);
        }

        if ($user->role !== 'landOwner') {
            return response()->json(['message' => 'Accès interdit. Vous devez être un propriétaire de terre.'], 403);
        }

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
    public function show(int $id)
    {
        $user = Auth::user();

        $land = Land::find($id);

        if (!$land) {
            return response()->json(['message' => 'Parcelle non trouvée.'], 404);
        }

        if ($land->user_id !== $user->id) {
            return response()->json(['message' => 'Accès interdit à cette parcelle.'], 403);
        }

        return response()->json([
            'message' => 'Parcelle récupérée avec succès.',
            'data' => $land
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateLandRequest $request, int $id)
    {
        $user = Auth::user();

        $land = Land::find($id);

        if (!$land) {
            return response()->json(['message' => 'Parcelle non trouvée.'], 404);
        }

        if ($land->user_id !== $user->id) {
            return response()->json(['message' => 'Accès interdit. Cette parcelle ne vous appartient pas.'], 403);
        }

        $data = $request->validated();

        // Si un nouveau document est uploadé
        if ($request->hasFile('ownershipdoc')) {
            // Supprimer l'ancien fichier s'il existe
            if ($land->ownershipdoc && Storage::disk('public')->exists($land->ownershipdoc)) {
                Storage::disk('public')->delete($land->ownershipdoc);
            }

            // Enregistrer le nouveau fichier
            $originalName = $request->file('ownershipdoc')->getClientOriginalName();
            $filename = time() . '_' . uniqid() . '_' . $originalName;
            $path = $request->file('ownershipdoc')->storeAs('documents', $filename, 'public');

            $data['ownershipdoc'] = $path;
        }

        $land->update($data);

        return response()->json([
            'message' => 'Parcelle mise à jour avec succès.',
            'data' => $land
        ]);
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
