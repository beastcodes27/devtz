<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTeamMemberRequest;
use App\Http\Requests\UpdateTeamMemberRequest;
use App\Models\TeamMember;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class TeamMemberController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $teamMembers = TeamMember::query()
            ->withCount('projects')
            ->orderBy('display_order')
            ->orderBy('name')
            ->paginate(12);

        return view('dashboard.team-members.index', ['teamMembers' => $teamMembers]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('dashboard.team-members.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTeamMemberRequest $request): RedirectResponse
    {
        TeamMember::create($this->teamMemberData($request));

        return to_route('dashboard.team-members.index')
            ->with('success', 'Team member added successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(TeamMember $teamMember): View
    {
        return view('dashboard.team-members.edit', ['teamMember' => $teamMember]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTeamMemberRequest $request, TeamMember $teamMember): RedirectResponse
    {
        $teamMember->update($this->teamMemberData($request));

        return to_route('dashboard.team-members.index')
            ->with('success', 'Team member updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TeamMember $teamMember): RedirectResponse
    {
        $teamMember->delete();

        return to_route('dashboard.team-members.index')
            ->with('success', 'Team member removed.');
    }

    /** @return array<string, mixed> */
    private function teamMemberData(
        StoreTeamMemberRequest|UpdateTeamMemberRequest $request,
    ): array {
        $data = $request->validated();
        $data['is_active'] = $request->boolean('is_active');
        $data['skills'] = collect(explode(',', $data['skills'] ?? ''))
            ->map(fn (string $skill): string => trim($skill))
            ->filter()
            ->unique()
            ->values()
            ->all();

        return $data;
    }
}
