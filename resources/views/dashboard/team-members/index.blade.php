@extends('dashboard.layouts.app')

@section('title', 'Team members')
@section('heading', 'Team members')
@section('subheading', 'Manage the people shown on your portfolio and assign them to delivery work.')

@section('actions')
    <a class="inline-flex items-center gap-2 rounded-xl bg-brand px-4 py-3 text-sm font-bold text-white shadow-lg shadow-brand/20 transition hover:-translate-y-0.5" href="{{ route('dashboard.team-members.create') }}"><span class="text-lg leading-none">+</span> Add team member</a>
@endsection

@section('content')
    <section class="grid gap-5 md:grid-cols-2 2xl:grid-cols-3">
        @forelse ($teamMembers as $teamMember)
            <article class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm">
                <div class="flex items-start gap-4">
                    @if ($teamMember->photoUrl())
                        <img class="size-16 rounded-2xl object-cover" src="{{ $teamMember->photoUrl() }}" alt="" loading="lazy" decoding="async">
                    @else
                        <span class="grid size-16 shrink-0 place-items-center rounded-2xl bg-ink text-base font-black text-white">{{ str($teamMember->name)->substr(0, 2)->upper() }}</span>
                    @endif
                    <div class="min-w-0 flex-1"><div class="flex items-start justify-between gap-3"><div><h2 class="truncate text-base font-black text-ink">{{ $teamMember->name }}</h2><p class="mt-1 text-xs font-semibold text-brand">{{ $teamMember->role }}</p></div><span @class(['mt-1 size-2.5 shrink-0 rounded-full', 'bg-emerald-500' => $teamMember->is_active, 'bg-slate-300' => ! $teamMember->is_active]) title="{{ $teamMember->is_active ? 'Active' : 'Hidden' }}"></span></div>@if ($teamMember->email)<a class="mt-3 block truncate text-xs text-slate-400 hover:text-brand" href="mailto:{{ $teamMember->email }}">{{ $teamMember->email }}</a>@endif</div>
                </div>
                @if ($teamMember->bio)<p class="mt-5 line-clamp-3 text-sm leading-6 text-slate-500">{{ $teamMember->bio }}</p>@endif
                @if ($teamMember->skills)<div class="mt-4 flex flex-wrap gap-2">@foreach ($teamMember->skills as $skill)<span class="rounded-full bg-slate-100 px-3 py-1 text-[10px] font-bold text-slate-600">{{ $skill }}</span>@endforeach</div>@endif
                <div class="mt-5 flex items-center justify-between border-t border-slate-100 pt-4"><span class="text-xs text-slate-400">{{ $teamMember->projects_count }} {{ str('project')->plural($teamMember->projects_count) }}</span><div class="flex items-center gap-2"><a class="rounded-lg border border-slate-200 px-3 py-2 text-xs font-bold text-slate-600 hover:border-brand hover:text-brand" href="{{ route('dashboard.team-members.edit', $teamMember) }}">Edit</a><form method="POST" action="{{ route('dashboard.team-members.destroy', $teamMember) }}" onsubmit="return confirm('Remove this team member?')">@csrf @method('DELETE')<button class="rounded-lg px-3 py-2 text-xs font-bold text-red-500 hover:bg-red-50" type="submit">Delete</button></form></div></div>
            </article>
        @empty
            <div class="col-span-full rounded-3xl border border-dashed border-slate-300 bg-white px-6 py-20 text-center"><span class="mx-auto grid size-14 place-items-center rounded-2xl bg-slate-100 text-2xl">+</span><h2 class="mt-4 text-lg font-black">Build your team directory</h2><p class="mt-2 text-sm text-slate-500">Add the people behind DevTZ and assign them to projects.</p><a class="mt-5 inline-flex rounded-xl bg-brand px-4 py-3 text-sm font-bold text-white" href="{{ route('dashboard.team-members.create') }}">Add first member</a></div>
        @endforelse
    </section>
    @if ($teamMembers->hasPages())<div class="mt-6">{{ $teamMembers->links() }}</div>@endif
@endsection
