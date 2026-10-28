@extends('dashboard.layouts.app')

@section('title', 'Client emails')
@section('heading', 'Client emails')
@section('subheading', 'Review portfolio inquiries and continue each conversation directly from the dashboard.')

@section('actions')
    <span class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm font-bold text-slate-600 shadow-sm">
        <span class="size-2 rounded-full bg-lime"></span>
        {{ $inquiries->count() }} {{ str('message')->plural($inquiries->count()) }}
    </span>
@endsection

@section('content')
    <section class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm">
        <form class="flex flex-col gap-3 border-b border-slate-100 p-4 sm:flex-row sm:p-5" method="GET" action="{{ route('dashboard.inquiries.index') }}">
            <label class="relative min-w-0 flex-1">
                <span class="sr-only">Search client emails</span>
                <svg class="pointer-events-none absolute left-4 top-1/2 size-5 -translate-y-1/2 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><circle cx="11" cy="11" r="6"/><path d="m16 16 4 4"/></svg>
                <input class="dashboard-input pl-12" type="search" name="search" value="{{ $search }}" placeholder="Search by client, email, company, or message">
            </label>
            <button class="rounded-xl bg-ink px-5 py-3 text-sm font-bold text-white transition hover:bg-brand" type="submit">Search inbox</button>
            @if ($search !== '')
                <a class="rounded-xl border border-slate-200 px-5 py-3 text-center text-sm font-bold text-slate-600" href="{{ route('dashboard.inquiries.index') }}">Clear</a>
            @endif
        </form>

        <div class="divide-y divide-slate-100">
            @forelse ($inquiries as $inquiry)
                <a class="grid gap-4 px-5 py-5 transition hover:bg-slate-50 sm:grid-cols-[minmax(0,1fr)_auto] sm:items-center sm:px-6" href="{{ route('dashboard.inquiries.show', $inquiry['id']) }}">
                    <div class="flex min-w-0 items-start gap-4">
                        <span class="grid size-11 shrink-0 place-items-center rounded-2xl bg-brand/10 text-sm font-black text-brand">{{ str($inquiry['name'] ?? '?')->substr(0, 2)->upper() }}</span>
                        <span class="min-w-0">
                            <span class="flex flex-wrap items-center gap-2">
                                <strong class="truncate text-sm text-ink">{{ $inquiry['name'] ?? 'Unknown client' }}</strong>
                                @if (! empty($inquiry['replies']))
                                    <small class="rounded-full bg-emerald-100 px-2.5 py-1 text-[10px] font-bold uppercase tracking-wide text-emerald-700">Replied</small>
                                @else
                                    <small class="rounded-full bg-amber-100 px-2.5 py-1 text-[10px] font-bold uppercase tracking-wide text-amber-700">New</small>
                                @endif
                            </span>
                            <span class="mt-1 block truncate text-xs text-slate-500">{{ $inquiry['email'] ?? '' }}{{ ! empty($inquiry['company']) ? ' · '.$inquiry['company'] : '' }}</span>
                            <span class="mt-2 line-clamp-2 block text-sm leading-6 text-slate-600">{{ $inquiry['message'] ?? '' }}</span>
                        </span>
                    </div>
                    <span class="flex items-center justify-between gap-4 pl-15 sm:flex-col sm:items-end sm:pl-0">
                        <small class="text-xs font-semibold text-slate-400">{{ isset($inquiry['submitted_at']) ? \Illuminate\Support\Carbon::parse($inquiry['submitted_at'])->diffForHumans() : '' }}</small>
                        <span class="text-xs font-bold text-brand">Open email →</span>
                    </span>
                </a>
            @empty
                <div class="px-6 py-20 text-center">
                    <span class="mx-auto grid size-14 place-items-center rounded-2xl bg-slate-100 text-2xl">✉</span>
                    <h2 class="mt-4 text-lg font-black">{{ $search !== '' ? 'No matching emails' : 'Your client inbox is clear' }}</h2>
                    <p class="mt-2 text-sm text-slate-500">{{ $search !== '' ? 'Try a different name, email, or keyword.' : 'New portfolio contact submissions will appear here.' }}</p>
                </div>
            @endforelse
        </div>
    </section>
@endsection
