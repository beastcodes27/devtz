<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#0c1424">
    <title>@yield('title', 'Dashboard') — DevTZ</title>
    <link rel="icon" href="{{ asset('favicon.svg') }}" type="image/svg+xml">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-slate-50 text-slate-950 antialiased">
    <a class="sr-only focus:not-sr-only focus:fixed focus:left-4 focus:top-4 focus:z-50 focus:rounded-xl focus:bg-white focus:px-4 focus:py-3" href="#main">Skip to content</a>

    <aside class="fixed inset-y-0 left-0 z-40 hidden w-72 flex-col bg-ink px-5 py-6 text-white lg:flex">
        <a class="flex items-center gap-3 px-3" href="{{ route('dashboard.index') }}">
            <span class="grid size-11 place-items-center rounded-2xl bg-lime text-sm font-black tracking-[-0.08em] text-ink">D/TZ</span>
            <span>
                <strong class="block text-sm tracking-tight">DevTZ Studio</strong>
                <small class="text-xs text-white/45">Portfolio manager</small>
            </span>
        </a>

        <nav class="mt-10 flex flex-1 flex-col gap-1" aria-label="Dashboard navigation">
            <a @class(['flex items-center gap-3 rounded-xl px-3 py-3 text-sm font-semibold transition', 'bg-white/10 text-white' => request()->routeIs('dashboard.index'), 'text-white/55 hover:bg-white/5 hover:text-white' => ! request()->routeIs('dashboard.index')]) href="{{ route('dashboard.index') }}">
                <svg class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path d="M4 13h6V4H4v9Zm10 7h6v-9h-6v9ZM4 20h6v-3H4v3Zm10-13h6V4h-6v3Z"/></svg>
                Overview
            </a>
            <a @class(['flex items-center gap-3 rounded-xl px-3 py-3 text-sm font-semibold transition', 'bg-white/10 text-white' => request()->routeIs('dashboard.projects.*'), 'text-white/55 hover:bg-white/5 hover:text-white' => ! request()->routeIs('dashboard.projects.*')]) href="{{ route('dashboard.projects.index') }}">
                <svg class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path d="M4 7.5h16m-16 0 2-3h5l2 3m-9 0V19h16V7.5M8 12h8"/></svg>
                Projects
            </a>
            <a @class(['flex items-center gap-3 rounded-xl px-3 py-3 text-sm font-semibold transition', 'bg-white/10 text-white' => request()->routeIs('dashboard.team-members.*'), 'text-white/55 hover:bg-white/5 hover:text-white' => ! request()->routeIs('dashboard.team-members.*')]) href="{{ route('dashboard.team-members.index') }}">
                <svg class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path d="M16 20v-1.5A3.5 3.5 0 0 0 12.5 15h-5A3.5 3.5 0 0 0 4 18.5V20m13-7a3 3 0 1 0 0-6m3 13v-1.5a3.5 3.5 0 0 0-2-3.16M10 12a4 4 0 1 0 0-8 4 4 0 0 0 0 8Z"/></svg>
                Team members
            </a>
            <a class="mt-5 flex items-center gap-3 rounded-xl border border-white/10 px-3 py-3 text-sm font-semibold text-white/55 transition hover:border-white/20 hover:text-white" href="{{ route('portfolio') }}" target="_blank">
                <svg class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path d="M14 4h6v6m0-6-9 9M19 14v5H5V5h5"/></svg>
                View live portfolio
            </a>
        </nav>

        <div class="rounded-2xl border border-white/10 bg-white/5 p-3">
            <div class="flex items-center gap-3">
                <span class="grid size-10 place-items-center rounded-xl bg-brand text-xs font-bold">{{ str(auth()->user()->name)->substr(0, 2)->upper() }}</span>
                <span class="min-w-0 flex-1">
                    <strong class="block truncate text-sm">{{ auth()->user()->name }}</strong>
                    <small class="block truncate text-white/40">{{ auth()->user()->email }}</small>
                </span>
            </div>
            <form class="mt-3" method="POST" action="{{ route('dashboard.logout') }}">
                @csrf
                <button class="w-full rounded-xl border border-white/10 px-3 py-2 text-left text-xs font-semibold text-white/60 transition hover:bg-white/10 hover:text-white" type="submit">Sign out</button>
            </form>
        </div>
    </aside>

    <div class="min-h-screen lg:pl-72">
        <header class="border-b border-slate-200 bg-white/90 px-5 py-4 backdrop-blur lg:hidden">
            <div class="flex items-center justify-between gap-3">
                <a class="flex items-center gap-2 font-bold" href="{{ route('dashboard.index') }}"><span class="grid size-9 place-items-center rounded-xl bg-ink text-[10px] font-black tracking-[-0.08em] text-white">D/TZ</span>Dashboard</a>
                <form method="POST" action="{{ route('dashboard.logout') }}">@csrf<button class="text-sm font-semibold text-slate-500" type="submit">Sign out</button></form>
            </div>
            <nav class="mt-4 flex gap-2 overflow-x-auto pb-1 text-sm font-semibold">
                <a class="whitespace-nowrap rounded-full bg-slate-100 px-4 py-2" href="{{ route('dashboard.index') }}">Overview</a>
                <a class="whitespace-nowrap rounded-full bg-slate-100 px-4 py-2" href="{{ route('dashboard.projects.index') }}">Projects</a>
                <a class="whitespace-nowrap rounded-full bg-slate-100 px-4 py-2" href="{{ route('dashboard.team-members.index') }}">Team</a>
            </nav>
        </header>

        <main id="main" class="mx-auto max-w-[1500px] px-5 py-7 sm:px-8 lg:px-10 lg:py-10">
            <div class="flex flex-col gap-5 sm:flex-row sm:items-end sm:justify-between">
                <div>
                    <p class="text-xs font-bold uppercase tracking-[0.18em] text-brand">DevTZ workspace</p>
                    <h1 class="mt-2 text-3xl font-black tracking-[-0.04em] text-ink sm:text-4xl">@yield('heading')</h1>
                    <p class="mt-2 max-w-2xl text-sm leading-6 text-slate-500">@yield('subheading')</p>
                </div>
                @hasSection('actions')
                    <div class="shrink-0">@yield('actions')</div>
                @endif
            </div>

            @if (session('success'))
                <div class="mt-6 flex items-center gap-3 rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-semibold text-emerald-800" role="status">
                    <span class="grid size-6 place-items-center rounded-full bg-emerald-600 text-xs text-white">✓</span>
                    {{ session('success') }}
                </div>
            @endif

            <div class="mt-8">@yield('content')</div>
        </main>
    </div>
</body>
</html>
