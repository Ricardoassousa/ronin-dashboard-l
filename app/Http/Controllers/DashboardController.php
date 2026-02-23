<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Display the dashboard.
     *
     * This method returns the main dashboard view with statistics
     * for the currently authenticated user.
     *
     * @return View
     */
    public function index(): View
    {
        try {
            $stats = [
                'users' => User::count(),
                'activities' => Activity::count()
            ];
            $recentActivities = Activity::with('user')->latest()->take(10)->get();

            $ordersPerDay = Activity::where('type', 'order')
                ->selectRaw('DATE(created_at) as date, COUNT(*) as total')
                ->where('created_at', '>=', now()->subDays(7))
                ->groupBy('date')
                ->orderBy('date')
                ->pluck('total', 'date');

            Log::info('Dashboard loaded successfully for user ID: ' . auth()->id());
            return view('dashboard', compact('stats', 'recentActivities', 'ordersPerDay'));

        } catch (Exception $e) {
            Log::error('DashboardController@index error: ' . $e->getMessage());
            return view('dashboard')->withErrors('Unable to load dashboard statistics at this time.');
        }
    }

}
