<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ config('app.name', 'Counter') }}</title>
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="min-h-screen bg-zinc-100 text-zinc-950 antialiased">
        <div class="min-h-screen lg:flex">
            <aside class="hidden w-64 border-r border-zinc-200 bg-white lg:block">
                <div class="flex h-16 items-center gap-3 border-b border-zinc-200 px-6">
                    <div class="flex size-9 items-center justify-center rounded-md bg-emerald-600 text-white">
                        <i data-lucide="package" class="size-5"></i>
                    </div>
                    <div>
                        <p class="text-sm font-semibold">Counter</p>
                        <p class="text-xs text-zinc-500">Controle de estoque</p>
                    </div>
                </div>

                <nav class="space-y-1 px-3 py-4">
                    <a href="{{ route('dashboard') }}" class="flex items-center gap-3 rounded-md bg-emerald-50 px-3 py-2 text-sm font-medium text-emerald-700">
                        <i data-lucide="layout-dashboard" class="size-4"></i>
                        Dashboard
                    </a>
                </nav>
            </aside>

            <div class="min-w-0 flex-1">
                <header class="flex h-16 items-center justify-between border-b border-zinc-200 bg-white px-4 sm:px-6">
                    <div>
                        <p class="text-sm font-semibold">{{ $title ?? 'Counter' }}</p>
                        <p class="text-xs text-zinc-500">{{ auth()->user()->company?->name ?? 'Empresa não definida' }}</p>
                    </div>

                    <div class="flex items-center gap-3">
                        <div class="hidden text-right sm:block">
                            <p class="text-sm font-medium">{{ auth()->user()->name }}</p>
                            <p class="text-xs text-zinc-500">{{ auth()->user()->email }}</p>
                        </div>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="inline-flex size-9 items-center justify-center rounded-md border border-zinc-200 text-zinc-600 transition hover:bg-zinc-50 hover:text-zinc-950" title="Sair">
                                <i data-lucide="log-out" class="size-4"></i>
                            </button>
                        </form>
                    </div>
                </header>

                <main class="p-4 sm:p-6">
                    {{ $slot }}
                </main>
            </div>
        </div>
    </body>
</html>
