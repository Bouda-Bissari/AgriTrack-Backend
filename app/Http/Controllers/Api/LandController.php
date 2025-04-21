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
