@extends('dashboard.layouts.app')

@section('title', 'Projects')
@section('heading', 'Projects')
@section('subheading', 'Plan delivery, track progress, assign your team, and choose the work featured on the public portfolio.')

@section('actions')
    <a class="inline-flex items-center gap-2 rounded-xl bg-brand px-4 py-3 text-sm font-bold text-white shadow-lg shadow-brand/20 transition hover:-translate-y-0.5" href="{{ route('dashboard.projects.create') }}"><span class="text-lg leading-none">+</span> Add project</a>
@endsection

@section('content')
    <section class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm">
        <form class="grid gap-3 border-b border-slate-100 p-4 sm:grid-cols-[minmax(0,1fr)_220px_auto] sm:p-5" method="GET" action="{{ route('dashboard.projects.index') }}">
            <label class="relative">
                <span class="sr-only">Search projects</span>
                <svg class="absolute left-4 top-1/2 size-4 -translate-y-1/2 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><circle cx="11" cy="11" r="7"/><path d="m20 20-4-4"/></svg>
                <input class="w-full rounded-xl border border-slate-200 bg-slate-50 py-3 pl-11 pr-4 text-sm outline-none focus:border-brand focus:ring-4 focus:ring-brand/10" type="search" name="search" value="{{ request('search') }}" placeholder="Search by project, client, or category">
            </label>
            <select class="rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm outline-none focus:border-brand focus:ring-4 focus:ring-brand/10" name="status">
                <option value="">All statuses</option>
                @foreach ($statuses as $status)
                    <option value="{{ $status->value }}" @selected(request('status') === $status->value)>{{ $status->label() }}</option>
                @endforeach
            </select>
            <button class="rounded-xl bg-ink px-5 py-3 text-sm font-bold text-white" type="submit">Filter</button>
        </form>

        <div class="overflow-x-auto">
            <table class="w-full min-w-[850px] text-left">
                <thead class="bg-slate-50 text-[10px] font-bold uppercase tracking-[0.14em] text-slate-400">
                    <tr><th class="px-6 py-4">Project</th><th class="px-4 py-4">Status</th><th class="px-4 py-4">Progress</th><th class="px-4 py-4">Team</th><th class="px-4 py-4">Visibility</th><th class="px-6 py-4 text-right">Actions</th></tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($projects as $project)
                        <tr class="transition hover:bg-slate-50/80">
                            <td class="px-6 py-5"><div class="flex items-center gap-3"><span @class(['grid size-11 shrink-0 place-items-center rounded-xl text-xs font-black', 'bg-lime text-ink' => $project->theme === 'lime', 'bg-orange-100 text-ink' => $project->theme === 'orange', 'bg-brand/10 text-brand' => $project->theme === 'violet'])>{{ str($project->title)->substr(0, 2)->upper() }}</span><span><strong class="block text-sm text-ink">{{ $project->title }}</strong><small class="mt-1 block text-slate-400">{{ $project->client ?: 'Internal' }} · {{ $project->category }}</small></span></div></td>
                            <td class="px-4 py-5"><span @class(['rounded-full px-3 py-1.5 text-[10px] font-bold uppercase tracking-wide', 'bg-emerald-100 text-emerald-700' => $project->status === \App\ProjectStatus::Completed, 'bg-violet-100 text-violet-700' => $project->status === \App\ProjectStatus::InProgress, 'bg-amber-100 text-amber-700' => $project->status === \App\ProjectStatus::Planning, 'bg-slate-100 text-slate-600' => in_array($project->status, [\App\ProjectStatus::Draft, \App\ProjectStatus::OnHold], true)])>{{ $project->status->label() }}</span></td>
                            <td class="px-4 py-5"><div class="w-32"><div class="flex justify-between text-[11px] font-bold text-slate-500"><span>{{ $project->progress }}%</span></div><div class="mt-2 h-1.5 overflow-hidden rounded-full bg-slate-100"><span class="block h-full rounded-full bg-brand" style="width: {{ $project->progress }}%"></span></div></div></td>
                            <td class="px-4 py-5 text-sm font-semibold text-slate-600">{{ $project->team_members_count }}</td>
                            <td class="px-4 py-5"><span class="text-xs font-semibold {{ $project->is_featured ? 'text-emerald-700' : 'text-slate-400' }}">{{ $project->is_featured ? 'Featured' : 'Dashboard only' }}</span></td>
                            <td class="px-6 py-5"><div class="flex items-center justify-end gap-2"><a class="rounded-lg border border-slate-200 px-3 py-2 text-xs font-bold text-slate-600 hover:border-brand hover:text-brand" href="{{ route('dashboard.projects.edit', $project) }}">Edit</a><form method="POST" action="{{ route('dashboard.projects.destroy', $project) }}" onsubmit="return confirm('Delete this project?')">@csrf @method('DELETE')<button class="rounded-lg px-3 py-2 text-xs font-bold text-red-500 hover:bg-red-50" type="submit">Delete</button></form></div></td>
                        </tr>
                    @empty
                        <tr><td class="px-6 py-16 text-center text-sm text-slate-500" colspan="6">No projects match this view. <a class="font-bold text-brand" href="{{ route('dashboard.projects.create') }}">Add a project</a>.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($projects->hasPages())
            <div class="border-t border-slate-100 px-5 py-4">{{ $projects->links() }}</div>
        @endif
    </section>
@endsection
