@extends('dashboard.layouts.app')

@section('title', 'Email from '.($inquiry['name'] ?? 'client'))
@section('heading', $inquiry['name'] ?? 'Client inquiry')
@section('subheading', 'Read the original project inquiry and send a direct email reply.')

@section('actions')
    <a class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm font-bold text-slate-600 shadow-sm transition hover:border-brand hover:text-brand" href="{{ route('dashboard.inquiries.index') }}">← Back to inbox</a>
@endsection

@section('content')
    <div class="grid gap-6 xl:grid-cols-[minmax(0,1fr)_minmax(360px,.72fr)]">
        <div class="flex flex-col gap-6">
            <article class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm">
                <header class="flex flex-col gap-4 border-b border-slate-100 px-5 py-5 sm:flex-row sm:items-start sm:justify-between sm:px-6">
                    <div class="flex items-center gap-4">
                        <span class="grid size-12 shrink-0 place-items-center rounded-2xl bg-brand text-sm font-black text-white">{{ str($inquiry['name'] ?? '?')->substr(0, 2)->upper() }}</span>
                        <div>
                            <h2 class="font-black text-ink">{{ $inquiry['name'] ?? 'Unknown client' }}</h2>
                            <a class="mt-1 block text-sm text-brand hover:underline" href="mailto:{{ $inquiry['email'] }}">{{ $inquiry['email'] }}</a>
                        </div>
                    </div>
                    <time class="text-xs font-semibold text-slate-400" datetime="{{ $inquiry['submitted_at'] ?? '' }}">{{ isset($inquiry['submitted_at']) ? \Illuminate\Support\Carbon::parse($inquiry['submitted_at'])->format('M j, Y · g:i A') : '' }}</time>
                </header>

                <div class="grid gap-4 border-b border-slate-100 bg-slate-50/70 px-5 py-5 text-sm sm:grid-cols-2 sm:px-6">
                    <div><span class="block text-xs font-bold uppercase tracking-[0.12em] text-slate-400">Company</span><strong class="mt-1.5 block text-slate-700">{{ $inquiry['company'] ?: 'Not provided' }}</strong></div>
                    <div><span class="block text-xs font-bold uppercase tracking-[0.12em] text-slate-400">Project type</span><strong class="mt-1.5 block text-slate-700">{{ str($inquiry['project_type'] ?? 'project')->replace('-', ' ')->title() }}</strong></div>
                </div>

                <div class="px-5 py-6 sm:px-6">
                    <p class="text-xs font-bold uppercase tracking-[0.12em] text-slate-400">Project brief</p>
                    <p class="mt-4 whitespace-pre-wrap text-sm leading-7 text-slate-700">{{ $inquiry['message'] }}</p>
                </div>
            </article>

            @if (! empty($inquiry['replies']))
                <section class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm">
                    <div class="border-b border-slate-100 px-5 py-5 sm:px-6"><h2 class="text-lg font-black tracking-tight">Reply history</h2><p class="mt-1 text-xs text-slate-500">Emails already sent to this client</p></div>
                    <div class="divide-y divide-slate-100">
                        @foreach (collect($inquiry['replies'])->reverse() as $reply)
                            <article class="px-5 py-5 sm:px-6">
                                <div class="flex flex-col gap-2 sm:flex-row sm:items-start sm:justify-between">
                                    <div><strong class="text-sm text-ink">{{ $reply['subject'] }}</strong><p class="mt-1 text-xs text-slate-400">Sent by {{ $reply['sent_by']['name'] ?? 'DevTZ admin' }}</p></div>
                                    <time class="text-xs font-semibold text-slate-400" datetime="{{ $reply['sent_at'] ?? '' }}">{{ isset($reply['sent_at']) ? \Illuminate\Support\Carbon::parse($reply['sent_at'])->format('M j, Y · g:i A') : '' }}</time>
                                </div>
                                <p class="mt-4 whitespace-pre-wrap text-sm leading-7 text-slate-600">{{ $reply['message'] }}</p>
                            </article>
                        @endforeach
                    </div>
                </section>
            @endif
        </div>

        <aside class="self-start rounded-3xl bg-ink p-5 text-white shadow-xl shadow-ink/10 sm:p-6 xl:sticky xl:top-8">
            <p class="text-xs font-bold uppercase tracking-[0.14em] text-lime">Direct reply</p>
            <h2 class="mt-2 text-2xl font-black tracking-tight">Email {{ str($inquiry['name'] ?? 'client')->before(' ') }}</h2>
            <p class="mt-2 text-sm leading-6 text-white/50">This message will be sent from the configured DevTZ email account to {{ $inquiry['email'] }}.</p>

            <form class="mt-6 flex flex-col gap-4" method="POST" action="{{ route('dashboard.inquiries.replies.store', $inquiry['id']) }}">
                @csrf
                <label class="flex flex-col gap-2 text-sm font-semibold">
                    Subject
                    <input class="rounded-xl border border-white/15 bg-white/10 px-4 py-3 text-sm text-white outline-none transition placeholder:text-white/25 focus:border-lime focus:ring-4 focus:ring-lime/10" type="text" name="subject" value="{{ old('subject', 'Re: '.str($inquiry['project_type'] ?? 'project')->replace('-', ' ')->title().' inquiry') }}" maxlength="150" required>
                    @error('subject')<small class="text-red-300">{{ $message }}</small>@enderror
                </label>
                <label class="flex flex-col gap-2 text-sm font-semibold">
                    Message
                    <textarea class="min-h-56 resize-y rounded-xl border border-white/15 bg-white/10 px-4 py-3 text-sm leading-6 text-white outline-none transition placeholder:text-white/25 focus:border-lime focus:ring-4 focus:ring-lime/10" name="message" maxlength="5000" placeholder="Write your reply to {{ $inquiry['name'] }}…" required>{{ old('message') }}</textarea>
                    @error('message')<small class="text-red-300">{{ $message }}</small>@enderror
                </label>
                <button class="inline-flex items-center justify-center gap-2 rounded-xl bg-lime px-5 py-3.5 text-sm font-black text-ink transition hover:-translate-y-0.5 hover:bg-white" type="submit">Send email <span aria-hidden="true">↗</span></button>
            </form>
        </aside>
    </div>
@endsection
