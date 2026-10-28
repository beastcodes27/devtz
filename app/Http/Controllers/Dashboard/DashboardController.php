<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\TeamMember;
use App\PortfolioInquiryStore;
use App\ProjectStatus;
use Illuminate\Contracts\View\View;

class DashboardController extends Controller
{
    public function __invoke(PortfolioInquiryStore $inquiryStore): View
    {
        $inquiries = $inquiryStore->all();

        return view('dashboard.index', [
            'projectCount' => Project::count(),
            'activeProjectCount' => Project::whereIn('status', [
                ProjectStatus::Planning,
                ProjectStatus::InProgress,
            ])->count(),
            'completedProjectCount' => Project::where('status', ProjectStatus::Completed)->count(),
            'teamMemberCount' => TeamMember::where('is_active', true)->count(),
            'recentProjects' => Project::query()
                ->withCount('teamMembers')
                ->orderByDesc('updated_at')
                ->orderByDesc('id')
                ->limit(5)
                ->get(),
            'inquiryCount' => $inquiries->count(),
            'recentInquiries' => $inquiries->take(4),
        ]);
    }
}
