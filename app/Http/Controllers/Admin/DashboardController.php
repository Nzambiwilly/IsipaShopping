<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\AdminDashboardMetrics;

class DashboardController extends Controller
{
    public function index(AdminDashboardMetrics $metrics)
    {
        return view('admin.dashboard', $metrics->snapshot());
    }
}
