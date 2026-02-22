<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminController extends Controller
{
    /**
     * Display the admin dashboard.
     *
     * This method returns the main dashboard view for administrators.
     *
     * @return View
     */
    public function index(): View
    {
        return view('admin.dashboard');
    }

}
