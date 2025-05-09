<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Intervention;
use App\Models\Land;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class UserStatsController extends Controller
{
    /**
     * Récupérer les statistiques personnelles de l'utilisateur.
     */
    public function getUserStats(User $user): JsonResponse
    {
        // Vérifier que l'utilisateur accède à ses propres données ou est admin
        $authUser = Auth::user();

        if ($authUser->id !== $user->id && $authUser->role !== 'admin') {
            return response()->json(['message' => 'Non autorisé'], 403);
        }
        
        // Récupérer les terres de l'utilisateur
        $lands = $user->lands()->pluck('id');

        // Statistiques de base
        $totalLands = $lands->count();
        $totalInterventions = Intervention::whereIn('land_id', $lands)->count();
        $completedInterventions = Intervention::whereIn('land_id', $lands)
            ->where('isDone', true)
            ->count();
        $pendingInterventions = Intervention::whereIn('land_id', $lands)
            ->where('isDone', false)
            ->count();

        return response()->json([
            'totalLands' => $totalLands,
            'totalInterventions' => $totalInterventions,
            'completedInterventions' => $completedInterventions,
            'pendingInterventions' => $pendingInterventions,
        ]);
    }

    /**
     * Récupérer les statistiques des interventions par type pour un utilisateur spécifique.
     */
    public function getUserInterventionStats(User $user): JsonResponse
    {
        // Vérifier que l'utilisateur accède à ses propres données ou est admin
        $authUser = Auth::user();

        if ($authUser->id !== $user->id && $authUser->role !== 'admin') {
            return response()->json(['message' => 'Non autorisé'], 403);
        }
        // Récupérer les terres de l'utilisateur
        $lands = $user->lands()->pluck('id');

        // Statistiques des interventions par type
        $stats = Intervention::whereIn('land_id', $lands)
            ->select(
                'type',
                DB::raw('COUNT(*) as total'),
                DB::raw('SUM(CASE WHEN isDone = 1 THEN 1 ELSE 0 END) as completed'),
                DB::raw('SUM(CASE WHEN isDone = 0 THEN 1 ELSE 0 END) as pending')
            )
            ->groupBy('type')
            ->orderByDesc('total')
            ->get();

        return response()->json($stats);
    }

    /**
     * Récupérer les statistiques des interventions par mois pour un utilisateur spécifique.
     */
    public function getUserMonthlyStats(User $user): JsonResponse
    {
        // Vérifier que l'utilisateur accède à ses propres données ou est admin
        $authUser = Auth::user();

        if ($authUser->id !== $user->id && $authUser->role !== 'admin') {
            return response()->json(['message' => 'Non autorisé'], 403);
        }
        // Récupérer les terres de l'utilisateur
        $lands = $user->lands()->pluck('id');

        // Statistiques des 6 derniers mois
        $sixMonthsAgo = Carbon::now()->subMonths(6)->startOfMonth();
        
        $monthlyStats = Intervention::whereIn('land_id', $lands)
            ->where('created_at', '>=', $sixMonthsAgo)
            ->select(
                DB::raw('YEAR(created_at) as year'),
                DB::raw('MONTH(created_at) as month'),
                DB::raw('COUNT(*) as total'),
                DB::raw('SUM(CASE WHEN isDone = 1 THEN 1 ELSE 0 END) as completed'),
                DB::raw('SUM(CASE WHEN isDone = 0 THEN 1 ELSE 0 END) as pending')
            )
            ->groupBy('year', 'month')
            ->orderBy('year')
            ->orderBy('month')
            ->get()
            ->map(function ($item) {
                $date = Carbon::createFromDate($item->year, $item->month, 1);
                return [
                    'month' => $date->format('M Y'),
                    'total' => $item->total,
                    'completed' => $item->completed,
                    'pending' => $item->pending
                ];
            });

        return response()->json($monthlyStats);
    }

    /**
     * Récupérer les statistiques des cultures par type pour un utilisateur spécifique.
     */
    public function getUserCultureStats(User $user): JsonResponse
    {
        // Vérifier que l'utilisateur accède à ses propres données ou est admin
        $authUser = Auth::user();

        if ($authUser->id !== $user->id && $authUser->role !== 'admin') {
            return response()->json(['message' => 'Non autorisé'], 403);
        }
        // Statistiques des terres par type de culture
        $cultureStats = Land::where('user_id', $user->id)
            ->select(
                'cultureType',
                DB::raw('COUNT(*) as count'),
                DB::raw('SUM(area) as totalArea')
            )
            ->groupBy('cultureType')
            ->orderByDesc('count')
            ->get();

        return response()->json($cultureStats);
    }
}