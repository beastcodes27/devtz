<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTeamMemberRequest;
use App\Http\Requests\UpdateTeamMemberRequest;
use App\Models\TeamMember;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use RuntimeException;
use Throwable;

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
        $storedPhotoPath = null;

        try {
            DB::transaction(function () use ($request, &$storedPhotoPath): void {
                $teamMember = TeamMember::create($this->teamMemberData($request));
                $storedPhotoPath = $this->storePhoto($teamMember, $request->file('photo'));
                $teamMember->update(['photo_path' => $storedPhotoPath]);
                $teamMember->user()->create([
                    'name' => $teamMember->name,
                    'email' => $teamMember->email,
                    'password' => $request->validated('password'),
                ]);
            });
        } catch (Throwable $exception) {
            Storage::disk('public')->delete(array_filter([$storedPhotoPath]));

            throw $exception;
        }

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
        $storedPhotoPath = null;
        $previousPhotoPath = $teamMember->photo_path;

        try {
            DB::transaction(function () use ($request, $teamMember, &$storedPhotoPath): void {
                $data = $this->teamMemberData($request);

                if ($request->hasFile('photo')) {
                    $storedPhotoPath = $this->storePhoto($teamMember, $request->file('photo'));
                    $data['photo_path'] = $storedPhotoPath;
                }

                $teamMember->update($data);

                $loginData = [
                    'name' => $teamMember->name,
                    'email' => $teamMember->email,
                ];

                if ($request->filled('password')) {
                    $loginData['password'] = $request->validated('password');
                }

                if ($teamMember->user) {
                    $teamMember->user->update($loginData);
                } elseif (isset($loginData['password'])) {
                    $teamMember->user()->create($loginData);
                }
            });
        } catch (Throwable $exception) {
            Storage::disk('public')->delete(array_filter([$storedPhotoPath]));

            throw $exception;
        }

        if ($storedPhotoPath !== null) {
            Storage::disk('public')->delete(array_filter([$previousPhotoPath]));
        }

        return to_route('dashboard.team-members.index')
            ->with('success', 'Team member updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TeamMember $teamMember): RedirectResponse
    {
        $photoPath = $teamMember->photo_path;
        $teamMember->delete();
        Storage::disk('public')->delete(array_filter([$photoPath]));

        return to_route('dashboard.team-members.index')
            ->with('success', 'Team member removed.');
    }

    /** @return array<string, mixed> */
    private function teamMemberData(
        StoreTeamMemberRequest|UpdateTeamMemberRequest $request,
    ): array {
        $data = $request->safe()->except(['password', 'password_confirmation', 'photo']);
        $data['is_active'] = $request->boolean('is_active');
        $data['skills'] = collect(explode(',', $data['skills'] ?? ''))
            ->map(fn (string $skill): string => trim($skill))
            ->filter()
            ->unique()
            ->values()
            ->all();

        return $data;
    }

    private function storePhoto(TeamMember $teamMember, ?UploadedFile $photo): string
    {
        if ($photo === null) {
            throw new RuntimeException('The team member photo could not be stored.');
        }

        $path = $photo->store('team-members/'.$teamMember->getKey(), 'public');

        if (! is_string($path)) {
            throw new RuntimeException('The team member photo could not be stored.');
        }

        return $path;
    }
}
