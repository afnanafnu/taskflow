<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Services\DashboardService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __construct(
        private readonly DashboardService $dashboardService
    ) {}

    public function index(
        Request $request
    ): View {
        $statistics = $this->dashboardService->getStatistics(
            $request->user()
        );

        return view(
            'dashboard.index',
            $statistics
        );
    }
}