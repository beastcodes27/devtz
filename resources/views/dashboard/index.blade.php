@extends('dashboard.layouts.app')

@section('title', 'Overview')
@section('heading', 'Good to see you, '.str(auth()->user()->name)->before(' '))
@section('subheading', 'A focused view of your portfolio, active delivery work, team, and new project inquiries.')

@section('actions')
    <a class="inline-flex items-center gap-2 rounded-xl bg-brand px-4 py-3 text-sm font-bold text-white shadow-lg shadow-brand/20 transition hover:-translate-y-0.5" href="{{ route('dashboard.projects.create') }}"><span class="text-lg leading-none">+</span> New project</a>
@endsection

@section('content')
    <section class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4" aria-label="Portfolio summary">
        @foreach ([
            ['label' => 'All projects', 'value' => $projectCount, 'note' => 'Portfolio records', 'color' => 'bg-brand'],
            ['label' => 'In delivery', 'value' => $activeProjectCount, 'note' => 'Planning or in progress', 'color' => 'bg-lime'],
            ['label' => 'Completed', 'value' => $completedProjectCount, 'note' => 'Successfully delivered', 'color' => 'bg-orange-400'],
            ['label' => 'Active team', 'value' => $teamMemberCount, 'note' => 'Available collaborators', 'color' => 'bg-ink'],
        ] as $stat)
            <article class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <p class="text-xs font-bold uppercase tracking-[0.14em] text-slate-400">{{ $stat['label'] }}</p>
                        <strong class="mt-3 block text-4xl font-black tracking-[-0.05em] text-ink">{{ $stat['value'] }}</strong>
                    </div>
                    <span class="size-3 rounded-full {{ $stat['color'] }}"></span>
                </div>
                <p class="mt-5 border-t border-slate-100 pt-4 text-xs text-slate-500">{{ $stat['note'] }}</p>
            </article>
        @endforeach
    </section>

    <div class="mt-6 grid gap-6 xl:grid-cols-[minmax(0,1.55fr)_minmax(320px,.75fr)]">
        <section class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm">
            <div class="flex items-center justify-between gap-4 border-b border-slate-100 px-5 py-5 sm:px-6">
                <div><h2 class="text-lg font-black tracking-tight">Recent projects</h2><p class="mt-1 text-xs text-slate-500">Latest activity across your work</p></div>
                <a class="text-xs font-bold text-brand hover:text-violet-800" href="{{ route('dashboard.projects.index') }}">View all →</a>
            </div>
            @forelse ($recentProjects as $project)
                <a class="grid gap-4 border-b border-slate-100 px-5 py-4 transition last:border-b-0 hover:bg-slate-50 sm:grid-cols-[minmax(0,1fr)_130px_90px] sm:items-center sm:px-6" href="{{ route('dashboard.projects.edit', $project) }}">
                    <div class="flex items-center gap-3">
                        <span class="grid size-10 shrink-0 place-items-center rounded-xl bg-slate-100 text-xs font-black text-ink">{{ str($project->title)->substr(0, 2)->upper() }}</span>
                        <span class="min-w-0"><strong class="block truncate text-sm">{{ $project->title }}</strong><small class="mt-1 block truncate text-slate-400">{{ $project->client ?: $project->category }} · {{ $project->team_members_count }} team</small></span>
                    </div>
                    <div>
                        <div class="flex items-center justify-between text-[11px] font-bold text-slate-500"><span>Progress</span><span>{{ $project->progress }}%</span></div>
                        <div class="mt-2 h-1.5 overflow-hidden rounded-full bg-slate-100"><span class="block h-full rounded-full bg-brand" style="width: {{ $project->progress }}%"></span></div>
                    </div>
                    <span class="justify-self-start rounded-full bg-slate-100 px-3 py-1.5 text-[10px] font-bold uppercase tracking-wide text-slate-600 sm:justify-self-end">{{ $project->status->label() }}</span>
                </a>
            @empty
                <div class="px-6 py-14 text-center"><p class="text-sm font-semibold text-slate-500">No projects yet.</p><a class="mt-3 inline-flex text-sm font-bold text-brand" href="{{ route('dashboard.projects.create') }}">Create your first project →</a></div>
            @endforelse
        </section>

        <section class="overflow-hidden rounded-3xl bg-ink text-white shadow-sm">
            <div class="flex items-start justify-between gap-4 border-b border-white/10 px-5 py-5 sm:px-6">
                <div><p class="text-xs font-bold uppercase tracking-[0.14em] text-lime">Project inquiries</p><h2 class="mt-2 text-2xl font-black tracking-tight">{{ $inquiryCount }} received</h2></div>
                <span class="grid size-10 place-items-center rounded-xl bg-white/10">↗</span>
            </div>
            <div class="divide-y divide-white/10">
                @forelse ($recentInquiries as $inquiry)
                    <article class="px-5 py-4 sm:px-6">
                        <div class="flex items-start justify-between gap-3"><strong class="text-sm">{{ $inquiry['name'] ?? 'Unknown' }}</strong><small class="text-[10px] text-white/35">{{ isset($inquiry['submitted_at']) ? \Illuminate\Support\Carbon::parse($inquiry['submitted_at'])->diffForHumans() : '' }}</small></div>
                        <p class="mt-1 text-xs text-white/45">{{ $inquiry['email'] ?? '' }} · {{ str($inquiry['project_type'] ?? 'project')->replace('-', ' ')->title() }}</p>
                        <p class="mt-3 line-clamp-2 text-xs leading-5 text-white/65">{{ $inquiry['message'] ?? '' }}</p>
                    </article>
                @empty
                    <div class="px-6 py-12 text-center text-sm text-white/45">New inquiries from the portfolio will appear here.</div>
                @endforelse
            </div>
        </section>
    </div>
@endsection
