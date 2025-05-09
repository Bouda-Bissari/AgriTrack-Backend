<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Requests\AdminCreateUserRequest;
use App\Http\Requests\UpdateUserStatusRequest;
use App\Models\Intervention;
use App\Models\Land;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{


    /**
 * Récupérer les statistiques du dashboard
 */
public function getDashboardStats(): JsonResponse
{
    $totalUsers = User::count();
    $blockedUsers = User::where('is_blocked', true)->count();
    $totalLands = Land::count();
    $totalInterventions = Intervention::count();

    return response()->json([
        'totalUsers' => $totalUsers,
        'blockedUsers' => $blockedUsers,
        'totalLands' => $totalLands,
        'totalInterventions' => $totalInterventions,
    ]);
}


    /**
 * Récupérer la liste de tous les utilisateurs.
 */
public function getAllUsers(Request $request): JsonResponse
{
    $query = User::query();

    // Appliquer les filtres si présents dans la requête
    if ($request->has('firstName')) {
        $query->where('firstName', 'like', '%' . $request->input('firstName') . '%');
    }

    if ($request->has('lastName')) {
        $query->where('lastName', 'like', '%' . $request->input('lastName') . '%');
    }

    if ($request->has('role')) {
        $query->where('role', $request->input('role'));
    }

    if ($request->has('phoneNumber')) {
        $query->where('phoneNumber', 'like', '%' . $request->input('phoneNumber') . '%');
    }

    $users = $query->get();

    return response()->json([
        'message' => 'Liste des utilisateurs filtrée avec succès.',
        'users' => $users,
    ]);
}



    /**
     * Créer un nouvel utilisateur admin.
     */
    public function createAdmin(AdminCreateUserRequest $request): JsonResponse
    {
        $user = User::create([
            'firstName' => $request->firstName,
            'lastName' => $request->lastName,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'phoneNumber' => $request->phoneNumber,
            'role' => 'admin',
        ]);

        return response()->json([
            'message' => 'Administrateur créé avec succès.',
            'user' => $user,
        ], 201);
    }

    /**
     * Supprimer un utilisateur par son ID.
     */
    public function deleteUser($id): JsonResponse
    {
        $user = User::findOrFail($id);

        $user->delete();

        return response()->json([
            'message' => "Utilisateur supprimé avec succès.",
        ]);
    }

    /**
     * Bloquer ou débloquer un utilisateur.
     */
    public function toggleBlockUser(UpdateUserStatusRequest $request, $id): JsonResponse
    {
        $user = User::findOrFail($id);
        $user->is_blocked = $request->is_blocked;
        $user->save();

        return response()->json([
            'message' => $request->is_blocked ? "Utilisateur bloqué." : "Utilisateur débloqué.",
            'user' => $user
        ]);
    }


    /**
 * Récupère les statistiques des parcelles par utilisateur
 */
public function getUserLandStats(): JsonResponse
{
    $stats = User::query()
        ->withCount('lands')
        ->whereHas('lands') // Seulement les utilisateurs avec au moins une parcelle
        ->orderByDesc('lands_count')
        ->limit(10) // Limite aux 10 premiers pour éviter la surcharge
        ->get(['id', 'first_name', 'last_name', 'lands_count'])
        ->map(function ($user) {
            return [
                'userId' => $user->id,
                'firstName' => $user->firstName,
                'lastName' => $user->lastName,
                'landCount' => $user->lands_count,
            ];
        });

    return response()->json($stats);
}


/**
 * Récupère les statistiques des interventions par type
 */
public function getInterventionStats(): JsonResponse
{
    $stats = Intervention::select(
            'type',
            DB::raw('COUNT(*) as count')
        )
        ->groupBy('type')
        ->orderByDesc('count')
        ->limit(5) // Limite aux 5 types les plus fréquents
        ->get()
        ->map(function ($item) {
            return [
                'type' => $item->type,
                'count' => $item->count
            ];
        });

    // Groupe les autres types dans "Autre" s'il y a plus de 5 types
    $otherCount = Intervention::whereNotIn('type', $stats->pluck('type'))
        ->count();

    if ($otherCount > 0) {
        $stats->push([
            'type' => 'other',
            'count' => $otherCount
        ]);
    }

    return response()->json($stats);
}


}