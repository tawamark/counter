<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ config('app.name', 'Counter') }}</title>
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="min-h-screen bg-[#f7f5f3] text-counter-text antialiased">
        <div class="min-h-screen lg:flex">
            <aside class="hidden w-64 border-r border-[#e5e0dc] bg-counter-bg lg:block">
                <div class="flex h-16 items-center border-b border-[#e5e0dc] px-6">
                    <img src="{{ asset('images/logo.svg') }}" alt="Counter" class="h-10 w-auto">
                </div>

                <nav class="space-y-1 px-3 py-4">
                    <a href="{{ route('dashboard') }}" class="flex items-center gap-3 rounded-md px-3 py-2 text-sm font-medium transition {{ request()->routeIs('dashboard') ? 'bg-orange-50 text-counter-primary' : 'text-[#6f6f6f] hover:bg-orange-50 hover:text-counter-primary' }}">
                        <i data-lucide="layout-dashboard" class="size-4"></i>
                        Dashboard
                    </a>
                    <a href="{{ route('products.index') }}" class="flex items-center gap-3 rounded-md px-3 py-2 text-sm font-medium transition {{ request()->routeIs('products.*') ? 'bg-orange-50 text-counter-primary' : 'text-[#6f6f6f] hover:bg-orange-50 hover:text-counter-primary' }}">
                        <i data-lucide="package" class="size-4"></i>
                        Produtos
                    </a>
                    <a href="{{ route('categories.index') }}" class="flex items-center gap-3 rounded-md px-3 py-2 text-sm font-medium transition {{ request()->routeIs('categories.*') ? 'bg-orange-50 text-counter-primary' : 'text-[#6f6f6f] hover:bg-orange-50 hover:text-counter-primary' }}">
                        <i data-lucide="tags" class="size-4"></i>
                        Categorias
                    </a>
                    <a href="{{ route('suppliers.index') }}" class="flex items-center gap-3 rounded-md px-3 py-2 text-sm font-medium transition {{ request()->routeIs('suppliers.*') ? 'bg-orange-50 text-counter-primary' : 'text-[#6f6f6f] hover:bg-orange-50 hover:text-counter-primary' }}">
                        <i data-lucide="truck" class="size-4"></i>
                        Fornecedores
                    </a>
                    <a href="{{ route('stock-movements.index') }}" class="flex items-center gap-3 rounded-md px-3 py-2 text-sm font-medium transition {{ request()->routeIs('stock-movements.*') ? 'bg-orange-50 text-counter-primary' : 'text-[#6f6f6f] hover:bg-orange-50 hover:text-counter-primary' }}">
                        <i data-lucide="arrow-down-up" class="size-4"></i>
                        Movimentações
                    </a>
                </nav>
            </aside>

            <div class="min-w-0 flex-1">
                <header class="flex h-16 items-center justify-between border-b border-[#e5e0dc] bg-counter-bg px-4 sm:px-6">
                    <div>
                        <p class="text-sm font-semibold">{{ $title ?? 'Counter' }}</p>
                        <p class="text-xs text-[#6f6f6f]">{{ auth()->user()->company?->name ?? 'Empresa não definida' }}</p>
                    </div>

                    <div class="flex items-center gap-3">
                        <div class="hidden text-right sm:block">
                            <p class="text-sm font-medium">{{ auth()->user()->name }}</p>
                            <p class="text-xs text-[#6f6f6f]">{{ auth()->user()->email }}</p>
                        </div>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="inline-flex size-9 items-center justify-center rounded-md border border-[#e5e0dc] text-[#6f6f6f] transition hover:bg-orange-50 hover:text-counter-primary" title="Sair">
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
