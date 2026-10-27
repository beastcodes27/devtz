<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\TeamMember;
use App\ProjectStatus;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use JsonException;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        $inquiries = $this->inquiries();

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

    /** @return Collection<int, array<string, mixed>> */
    private function inquiries(): Collection
    {
        return collect(Storage::disk('local')->files('portfolio-inquiries'))
            ->map(function (string $path): ?array {
                try {
                    $inquiry = json_decode(
                        Storage::disk('local')->get($path),
                        true,
                        flags: JSON_THROW_ON_ERROR,
                    );

                    return is_array($inquiry) ? $inquiry : null;
                } catch (JsonException) {
                    return null;
                }
            })
            ->filter()
            ->sortByDesc('submitted_at')
            ->values();
    }
}
