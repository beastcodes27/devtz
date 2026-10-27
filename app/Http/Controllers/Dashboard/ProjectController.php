<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProjectRequest;
use App\Http\Requests\UpdateProjectRequest;
use App\Models\Project;
use App\Models\TeamMember;
use App\ProjectStatus;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $projects = Project::query()
            ->withCount('teamMembers')
            ->when(request()->filled('search'), function ($query): void {
                $search = request()->string('search')->trim()->toString();

                $query->where(function ($query) use ($search): void {
                    $query->where('title', 'like', "%{$search}%")
                        ->orWhere('client', 'like', "%{$search}%")
                        ->orWhere('category', 'like', "%{$search}%");
                });
            })
            ->when(request()->enum('status', ProjectStatus::class), function ($query, ProjectStatus $status): void {
                $query->where('status', $status);
            })
            ->orderByDesc('updated_at')
            ->orderByDesc('id')
            ->paginate(10)
            ->withQueryString();

        return view('dashboard.projects.index', [
            'projects' => $projects,
            'statuses' => ProjectStatus::cases(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('dashboard.projects.create', [
            'statuses' => ProjectStatus::cases(),
            'teamMembers' => TeamMember::active()->get(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProjectRequest $request): RedirectResponse
    {
        DB::transaction(function () use ($request): void {
            $project = Project::create($this->projectData($request));
            $project->teamMembers()->sync(Arr::get($request->validated(), 'team_member_ids', []));
        });

        return to_route('dashboard.projects.index')
            ->with('success', 'Project created successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Project $project): View
    {
        $project->load('teamMembers:id');

        return view('dashboard.projects.edit', [
            'project' => $project,
            'statuses' => ProjectStatus::cases(),
            'teamMembers' => TeamMember::active()->get(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProjectRequest $request, Project $project): RedirectResponse
    {
        DB::transaction(function () use ($request, $project): void {
            $project->update($this->projectData($request, $project));
            $project->teamMembers()->sync(Arr::get($request->validated(), 'team_member_ids', []));
        });

        return to_route('dashboard.projects.index')
            ->with('success', 'Project updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Project $project): RedirectResponse
    {
        $project->delete();

        return to_route('dashboard.projects.index')
            ->with('success', 'Project deleted.');
    }

    /** @return array<string, mixed> */
    private function projectData(
        StoreProjectRequest|UpdateProjectRequest $request,
        ?Project $project = null,
    ): array {
        $data = $request->safe()->except(['team_member_ids']);
        $data['is_featured'] = $request->boolean('is_featured');
        $data['tags'] = collect(explode(',', $data['tags'] ?? ''))
            ->map(fn (string $tag): string => trim($tag))
            ->filter()
            ->unique()
            ->values()
            ->all();
        $data['slug'] = $this->uniqueSlug($data['title'], $project);

        return $data;
    }

    private function uniqueSlug(string $title, ?Project $project = null): string
    {
        $baseSlug = Str::slug($title) ?: 'project';
        $slug = $baseSlug;
        $suffix = 2;

        while (Project::query()
            ->where('slug', $slug)
            ->when($project, fn ($query) => $query->whereKeyNot($project->getKey()))
            ->exists()) {
            $slug = $baseSlug.'-'.$suffix;
            $suffix++;
        }

        return $slug;
    }
}
