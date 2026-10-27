<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#0c1424">
    <title>Dashboard sign in — DevTZ</title>
    <link rel="icon" href="{{ asset('favicon.svg') }}" type="image/svg+xml">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-ink text-white antialiased">
    <main class="grid min-h-screen lg:grid-cols-[1.05fr_.95fr]">
        <section class="relative hidden overflow-hidden border-r border-white/10 p-12 lg:flex lg:flex-col lg:justify-between">
            <div class="absolute -right-24 -top-24 size-[34rem] rounded-full border border-white/10"></div>
            <div class="absolute -right-8 top-4 size-[25rem] rounded-full border border-white/5"></div>
            <a class="relative flex items-center gap-3" href="{{ route('portfolio') }}">
                <span class="grid size-11 place-items-center rounded-2xl bg-lime text-sm font-black tracking-[-0.08em] text-ink">D/TZ</span>
                <span class="text-sm font-bold">DevTZ Studio</span>
            </a>
            <div class="relative max-w-xl">
                <p class="text-xs font-bold uppercase tracking-[0.2em] text-lime">Studio operations</p>
                <h1 class="mt-5 text-6xl font-black leading-[.95] tracking-[-0.065em]">Keep the work clear. Keep the team moving.</h1>
                <p class="mt-7 max-w-md text-base leading-7 text-white/55">Manage featured work, delivery progress, and the people behind every DevTZ project.</p>
            </div>
            <p class="relative text-xs text-white/30">Private workspace · Authorized access only</p>
        </section>

        <section class="flex items-center justify-center bg-slate-50 px-5 py-12 text-ink sm:px-10">
            <div class="w-full max-w-md">
                <a class="mb-12 flex items-center gap-3 lg:hidden" href="{{ route('portfolio') }}"><span class="grid size-10 place-items-center rounded-xl bg-ink text-[11px] font-black tracking-[-0.08em] text-white">D/TZ</span><strong>DevTZ Studio</strong></a>
                <p class="text-xs font-bold uppercase tracking-[0.18em] text-brand">Welcome back</p>
                <h2 class="mt-3 text-4xl font-black tracking-[-0.05em]">Sign in to your dashboard</h2>
                <p class="mt-3 text-sm leading-6 text-slate-500">Use the administrator account created on your server.</p>

                <form class="mt-9 flex flex-col gap-5" method="POST" action="{{ route('login.store') }}">
                    @csrf
                    <label class="flex flex-col gap-2 text-sm font-semibold">
                        Email address
                        <input class="rounded-2xl border border-slate-200 bg-white px-4 py-3.5 text-sm outline-none transition placeholder:text-slate-300 focus:border-brand focus:ring-4 focus:ring-brand/10" type="email" name="email" value="{{ old('email') }}" autocomplete="email" placeholder="you@devtz.com" required autofocus>
                        @error('email')<span class="text-xs font-medium text-red-600">{{ $message }}</span>@enderror
                    </label>
                    <label class="flex flex-col gap-2 text-sm font-semibold">
                        Password
                        <input class="rounded-2xl border border-slate-200 bg-white px-4 py-3.5 text-sm outline-none transition placeholder:text-slate-300 focus:border-brand focus:ring-4 focus:ring-brand/10" type="password" name="password" autocomplete="current-password" placeholder="Your password" required>
                        @error('password')<span class="text-xs font-medium text-red-600">{{ $message }}</span>@enderror
                    </label>
                    <label class="flex items-center gap-3 text-sm text-slate-600">
                        <input class="size-4 rounded border-slate-300 text-brand focus:ring-brand" type="checkbox" name="remember" value="1">
                        Keep me signed in
                    </label>
                    <button class="mt-2 rounded-2xl bg-brand px-5 py-4 text-sm font-bold text-white shadow-lg shadow-brand/20 transition hover:-translate-y-0.5 hover:bg-violet-700" type="submit">Sign in to dashboard</button>
                </form>
                <a class="mt-8 inline-flex text-sm font-semibold text-slate-500 hover:text-ink" href="{{ route('portfolio') }}">← Back to portfolio</a>
            </div>
        </section>
    </main>
</body>
</html>
