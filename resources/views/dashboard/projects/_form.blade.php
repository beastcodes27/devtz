@php
    $editingProject = $project ?? null;
    $selectedTeamMembers = old('team_member_ids', $editingProject?->teamMembers->pluck('id')->all() ?? []);
@endphp

@if ($errors->any())
    <div class="rounded-2xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700" role="alert">Please review the highlighted fields and try again.</div>
@endif

<div class="grid gap-6 xl:grid-cols-[minmax(0,1.35fr)_minmax(320px,.65fr)]">
    <div class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm sm:p-7">
        <h2 class="text-lg font-black tracking-tight">Project details</h2>
        <div class="mt-6 grid gap-5 sm:grid-cols-2">
            <label class="sm:col-span-2 flex flex-col gap-2 text-sm font-semibold">Project title<input class="dashboard-input" type="text" name="title" value="{{ old('title', $editingProject?->title) }}" maxlength="150" required>@error('title')<small class="text-red-600">{{ $message }}</small>@enderror</label>
            <label class="flex flex-col gap-2 text-sm font-semibold">Client<input class="dashboard-input" type="text" name="client" value="{{ old('client', $editingProject?->client) }}" maxlength="150" placeholder="Optional">@error('client')<small class="text-red-600">{{ $message }}</small>@enderror</label>
            <label class="flex flex-col gap-2 text-sm font-semibold">Category<input class="dashboard-input" type="text" name="category" value="{{ old('category', $editingProject?->category) }}" maxlength="100" placeholder="Website, web app, branding…" required>@error('category')<small class="text-red-600">{{ $message }}</small>@enderror</label>
            <label class="sm:col-span-2 flex flex-col gap-2 text-sm font-semibold">Short summary<textarea class="dashboard-input min-h-28 resize-y" name="summary" maxlength="500" required>{{ old('summary', $editingProject?->summary) }}</textarea><small class="font-normal text-slate-400">Shown on the public portfolio when this project is featured.</small>@error('summary')<small class="text-red-600">{{ $message }}</small>@enderror</label>
            <label class="sm:col-span-2 flex flex-col gap-2 text-sm font-semibold">Internal notes<textarea class="dashboard-input min-h-36 resize-y" name="description" maxlength="5000">{{ old('description', $editingProject?->description) }}</textarea>@error('description')<small class="text-red-600">{{ $message }}</small>@enderror</label>
            <label class="flex flex-col gap-2 text-sm font-semibold">Project link<input class="dashboard-input" type="url" name="project_url" value="{{ old('project_url', $editingProject?->project_url) }}" placeholder="https://example.com">@error('project_url')<small class="text-red-600">{{ $message }}</small>@enderror</label>
            <label class="flex flex-col gap-2 text-sm font-semibold">Tags<input class="dashboard-input" type="text" name="tags" value="{{ old('tags', implode(', ', $editingProject?->tags ?? [])) }}" placeholder="Laravel, UX design, API"><small class="font-normal text-slate-400">Separate tags with commas.</small>@error('tags')<small class="text-red-600">{{ $message }}</small>@enderror</label>
        </div>
    </div>

    <div class="flex flex-col gap-6">
        <div class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm sm:p-6">
            <h2 class="text-lg font-black tracking-tight">Delivery</h2>
            <div class="mt-5 flex flex-col gap-5">
                <label class="flex flex-col gap-2 text-sm font-semibold">Status<select class="dashboard-input" name="status" required>@foreach ($statuses as $status)<option value="{{ $status->value }}" @selected(old('status', $editingProject?->status?->value ?? \App\ProjectStatus::Draft->value) === $status->value)>{{ $status->label() }}</option>@endforeach</select>@error('status')<small class="text-red-600">{{ $message }}</small>@enderror</label>
                <label class="flex flex-col gap-2 text-sm font-semibold">Progress (%)<input class="dashboard-input" type="number" name="progress" value="{{ old('progress', $editingProject?->progress ?? 0) }}" min="0" max="100" required>@error('progress')<small class="text-red-600">{{ $message }}</small>@enderror</label>
                <div class="grid grid-cols-2 gap-3"><label class="flex flex-col gap-2 text-sm font-semibold">Start date<input class="dashboard-input" type="date" name="started_on" value="{{ old('started_on', $editingProject?->started_on?->format('Y-m-d')) }}">@error('started_on')<small class="text-red-600">{{ $message }}</small>@enderror</label><label class="flex flex-col gap-2 text-sm font-semibold">Due date<input class="dashboard-input" type="date" name="due_on" value="{{ old('due_on', $editingProject?->due_on?->format('Y-m-d')) }}">@error('due_on')<small class="text-red-600">{{ $message }}</small>@enderror</label></div>
            </div>
        </div>

        <div class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm sm:p-6">
            <h2 class="text-lg font-black tracking-tight">Portfolio display</h2>
            <div class="mt-5 flex flex-col gap-5">
                <label class="flex items-start gap-3 rounded-2xl bg-slate-50 p-4"><input class="mt-0.5 size-4 rounded border-slate-300 text-brand focus:ring-brand" type="checkbox" name="is_featured" value="1" @checked(old('is_featured', $editingProject?->is_featured ?? false))><span><strong class="block text-sm">Feature on portfolio</strong><small class="mt-1 block text-xs leading-5 text-slate-500">Draft projects stay hidden even when selected.</small></span></label>
                <label class="flex flex-col gap-2 text-sm font-semibold">Visual theme<select class="dashboard-input" name="theme" required><option value="violet" @selected(old('theme', $editingProject?->theme ?? 'violet') === 'violet')>Violet</option><option value="lime" @selected(old('theme', $editingProject?->theme) === 'lime')>Lime</option><option value="orange" @selected(old('theme', $editingProject?->theme) === 'orange')>Orange</option></select></label>
                <label class="flex flex-col gap-2 text-sm font-semibold">Display order<input class="dashboard-input" type="number" name="display_order" value="{{ old('display_order', $editingProject?->display_order ?? 0) }}" min="0" max="999" required></label>
            </div>
        </div>
    </div>
</div>

<div class="mt-6 rounded-3xl border border-slate-200 bg-white p-5 shadow-sm sm:p-7">
    <div class="flex flex-col gap-2 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <h2 class="text-lg font-black tracking-tight">Project screenshots</h2>
            <p class="mt-1 text-sm text-slate-500">Add up to 12 JPG, PNG, or WebP images at once. Each image can be up to 5 MB.</p>
        </div>
        <span class="text-xs font-bold uppercase tracking-[0.14em] text-brand">Auto-slides every 2 seconds</span>
    </div>

    @if ($editingProject?->images->isNotEmpty())
        <div class="mt-5 grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
            @foreach ($editingProject->images as $image)
                <label class="group cursor-pointer overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm transition hover:-translate-y-1 hover:border-brand/30 hover:shadow-lg has-checked:border-red-300 has-checked:bg-red-50 has-checked:opacity-70">
                    <span class="relative block bg-slate-100 p-2">
                        <img class="aspect-video w-full rounded-xl bg-white object-contain shadow-sm" src="{{ $image->url() }}" alt="{{ $editingProject->title }} screenshot {{ $loop->iteration }}" loading="lazy" decoding="async">
                        <span class="absolute left-4 top-4 rounded-full bg-ink/85 px-2.5 py-1 text-[9px] font-bold uppercase tracking-[0.12em] text-white backdrop-blur">Screenshot {{ str_pad((string) $loop->iteration, 2, '0', STR_PAD_LEFT) }}</span>
                    </span>
                    <span class="flex items-center justify-between gap-3 border-t border-slate-100 px-4 py-3">
                        <span><strong class="block text-xs text-ink">Portfolio image</strong><small class="mt-0.5 block text-[10px] font-normal text-slate-400">Shown in this order</small></span>
                        <span class="flex items-center gap-2 text-xs font-semibold text-red-600">
                            <input class="size-4 rounded border-slate-300 text-red-600 focus:ring-red-500" type="checkbox" name="remove_image_ids[]" value="{{ $image->id }}" @checked(in_array($image->id, old('remove_image_ids', [])))>
                            Remove
                        </span>
                    </span>
                </label>
            @endforeach
        </div>
        @error('remove_image_ids.*')<small class="mt-3 block text-red-600">{{ $message }}</small>@enderror
    @endif

    <label class="group mt-5 flex cursor-pointer flex-col items-center justify-center rounded-2xl border-2 border-dashed border-slate-300 bg-gradient-to-br from-slate-50 to-brand/5 px-5 py-10 text-center transition hover:border-brand hover:shadow-lg hover:shadow-brand/5">
        <span class="grid size-12 place-items-center rounded-2xl bg-brand text-2xl text-white shadow-lg shadow-brand/20 transition group-hover:-translate-y-1" aria-hidden="true">+</span>
        <strong class="mt-4 text-sm">Add more screenshots</strong>
        <small class="mt-1 text-xs text-slate-400" data-screenshot-selection>Choose one or several images from your device</small>
        <span class="mt-4 rounded-full border border-slate-200 bg-white px-3 py-1.5 text-[10px] font-bold uppercase tracking-[0.12em] text-slate-500">JPG · PNG · WebP · 5 MB max</span>
        <input class="sr-only" type="file" name="screenshots[]" accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp" multiple data-screenshot-input>
    </label>
    @error('screenshots')<small class="mt-3 block text-red-600">{{ $message }}</small>@enderror
    @error('screenshots.*')<small class="mt-3 block text-red-600">{{ $message }}</small>@enderror
</div>

<div class="mt-6 rounded-3xl border border-slate-200 bg-white p-5 shadow-sm sm:p-7">
    <h2 class="text-lg font-black tracking-tight">Assigned team</h2>
    <p class="mt-1 text-sm text-slate-500">Choose everyone contributing to this project.</p>
    <div class="mt-5 grid gap-3 sm:grid-cols-2 xl:grid-cols-3">
        @forelse ($teamMembers as $teamMember)
            <label class="flex cursor-pointer items-center gap-3 rounded-2xl border border-slate-200 p-3 transition has-checked:border-brand has-checked:bg-brand/5">
                <input class="size-4 rounded border-slate-300 text-brand focus:ring-brand" type="checkbox" name="team_member_ids[]" value="{{ $teamMember->id }}" @checked(in_array($teamMember->id, $selectedTeamMembers))>
                <span class="grid size-9 place-items-center rounded-xl bg-ink text-[10px] font-bold text-white">{{ str($teamMember->name)->substr(0, 2)->upper() }}</span>
                <span><strong class="block text-sm">{{ $teamMember->name }}</strong><small class="text-xs text-slate-400">{{ $teamMember->role }}</small></span>
            </label>
        @empty
            <p class="text-sm text-slate-500">No active team members yet. <a class="font-bold text-brand" href="{{ route('dashboard.team-members.create') }}">Add one first</a>.</p>
        @endforelse
    </div>
    @error('team_member_ids.*')<small class="mt-3 block text-red-600">{{ $message }}</small>@enderror
</div>

<div class="mt-6 flex flex-col-reverse gap-3 sm:flex-row sm:justify-end">
    <a class="rounded-xl border border-slate-200 bg-white px-5 py-3 text-center text-sm font-bold text-slate-600" href="{{ route('dashboard.projects.index') }}">Cancel</a>
    <button class="rounded-xl bg-brand px-6 py-3 text-sm font-bold text-white shadow-lg shadow-brand/20" type="submit">{{ $submitLabel }}</button>
</div>
