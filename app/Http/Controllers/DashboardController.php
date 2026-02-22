<?php

namespace App\Http\Controllers;

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
            ];

            Log::info('Dashboard loaded successfully for user ID: ' . auth()->id());
            return view('dashboard', compact('stats'));

        } catch (Exception $e) {
            Log::error('DashboardController@index error: ' . $e->getMessage());
            return view('dashboard')->withErrors('Unable to load dashboard statistics at this time.');
        }
    }

}
