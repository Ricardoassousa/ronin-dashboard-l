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
        return view('dashboard');
    }

}
