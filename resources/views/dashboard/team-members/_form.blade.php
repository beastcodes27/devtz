@php($editingTeamMember = $teamMember ?? null)

@if ($errors->any())
    <div class="mb-6 rounded-2xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700" role="alert">Please review the highlighted fields and try again.</div>
@endif

<div class="grid gap-6 xl:grid-cols-[minmax(0,1.25fr)_minmax(300px,.75fr)]">
    <div class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm sm:p-7">
        <h2 class="text-lg font-black tracking-tight">Profile details</h2>
        <div class="mt-6 grid gap-5 sm:grid-cols-2">
            <label class="flex flex-col gap-2 text-sm font-semibold">Full name<input class="dashboard-input" type="text" name="name" value="{{ old('name', $editingTeamMember?->name) }}" maxlength="120" required>@error('name')<small class="text-red-600">{{ $message }}</small>@enderror</label>
            <label class="flex flex-col gap-2 text-sm font-semibold">Role<input class="dashboard-input" type="text" name="role" value="{{ old('role', $editingTeamMember?->role) }}" maxlength="120" placeholder="Lead developer" required>@error('role')<small class="text-red-600">{{ $message }}</small>@enderror</label>
            <label class="flex flex-col gap-2 text-sm font-semibold">Email address<input class="dashboard-input" type="email" name="email" value="{{ old('email', $editingTeamMember?->email) }}" maxlength="180" placeholder="Optional">@error('email')<small class="text-red-600">{{ $message }}</small>@enderror</label>
            <label class="flex flex-col gap-2 text-sm font-semibold">Avatar image URL<input class="dashboard-input" type="url" name="avatar_url" value="{{ old('avatar_url', $editingTeamMember?->avatar_url) }}" placeholder="https://…">@error('avatar_url')<small class="text-red-600">{{ $message }}</small>@enderror</label>
            <label class="sm:col-span-2 flex flex-col gap-2 text-sm font-semibold">Short bio<textarea class="dashboard-input min-h-36 resize-y" name="bio" maxlength="1000" placeholder="A short introduction for the public portfolio…">{{ old('bio', $editingTeamMember?->bio) }}</textarea>@error('bio')<small class="text-red-600">{{ $message }}</small>@enderror</label>
            <label class="sm:col-span-2 flex flex-col gap-2 text-sm font-semibold">Skills<input class="dashboard-input" type="text" name="skills" value="{{ old('skills', implode(', ', $editingTeamMember?->skills ?? [])) }}" placeholder="Laravel, UI design, product strategy"><small class="font-normal text-slate-400">Separate skills with commas.</small>@error('skills')<small class="text-red-600">{{ $message }}</small>@enderror</label>
        </div>
    </div>

    <div class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm sm:p-6">
        <h2 class="text-lg font-black tracking-tight">Portfolio settings</h2>
        <div class="mt-5 flex flex-col gap-5">
            <label class="flex items-start gap-3 rounded-2xl bg-slate-50 p-4"><input class="mt-0.5 size-4 rounded border-slate-300 text-brand focus:ring-brand" type="checkbox" name="is_active" value="1" @checked(old('is_active', $editingTeamMember?->is_active ?? true))><span><strong class="block text-sm">Active team member</strong><small class="mt-1 block text-xs leading-5 text-slate-500">Active members can appear publicly and be assigned to projects.</small></span></label>
            <label class="flex flex-col gap-2 text-sm font-semibold">Display order<input class="dashboard-input" type="number" name="display_order" value="{{ old('display_order', $editingTeamMember?->display_order ?? 0) }}" min="0" max="999" required><small class="font-normal text-slate-400">Lower numbers appear first.</small>@error('display_order')<small class="text-red-600">{{ $message }}</small>@enderror</label>
        </div>
    </div>
</div>

<div class="mt-6 flex flex-col-reverse gap-3 sm:flex-row sm:justify-end">
    <a class="rounded-xl border border-slate-200 bg-white px-5 py-3 text-center text-sm font-bold text-slate-600" href="{{ route('dashboard.team-members.index') }}">Cancel</a>
    <button class="rounded-xl bg-brand px-6 py-3 text-sm font-bold text-white shadow-lg shadow-brand/20" type="submit">{{ $submitLabel }}</button>
</div>
