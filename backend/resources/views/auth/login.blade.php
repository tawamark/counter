<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Entrar | {{ config('app.name', 'Counter') }}</title>
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="min-h-screen bg-zinc-100 text-zinc-950 antialiased">
        <main class="grid min-h-screen lg:grid-cols-[1fr_440px]">
            <section class="hidden bg-zinc-950 px-10 py-12 text-white lg:flex lg:flex-col lg:justify-between">
                <div class="flex items-center gap-3">
                    <div class="flex size-10 items-center justify-center rounded-md bg-emerald-500 text-white">
                        <i data-lucide="package" class="size-5"></i>
                    </div>
                    <div>
                        <p class="font-semibold">Counter</p>
                        <p class="text-sm text-zinc-400">Controle e contagem de estoque</p>
                    </div>
                </div>

                <div class="max-w-xl">
                    <p class="text-sm font-medium text-emerald-300">Sistema web e mobile</p>
                    <h1 class="mt-4 text-4xl font-semibold tracking-normal">Gerencie produtos, movimentações e contagens físicas em um único fluxo.</h1>
                    <p class="mt-4 max-w-lg text-base text-zinc-300">Acesse o painel administrativo para acompanhar o estoque, registrar operações e analisar divergências.</p>
                </div>
            </section>

            <section class="flex items-center justify-center px-4 py-10 sm:px-6">
                <div class="w-full max-w-sm">
                    <div class="mb-8 lg:hidden">
                        <div class="mb-4 flex size-10 items-center justify-center rounded-md bg-emerald-600 text-white">
                            <i data-lucide="package" class="size-5"></i>
                        </div>
                        <h1 class="text-2xl font-semibold">Counter</h1>
                        <p class="mt-1 text-sm text-zinc-500">Controle e contagem de estoque</p>
                    </div>

                    <div class="rounded-lg border border-zinc-200 bg-white p-6 shadow-sm">
                        <div class="mb-6">
                            <h2 class="text-xl font-semibold">Entrar</h2>
                            <p class="mt-1 text-sm text-zinc-500">Informe suas credenciais para acessar o sistema.</p>
                        </div>

                        <form method="POST" action="{{ route('login.store') }}" class="space-y-4">
                            @csrf

                            <div>
                                <label for="email" class="mb-1 block text-sm font-medium">E-mail</label>
                                <input id="email" name="email" type="email" value="{{ old('email') }}" required autofocus autocomplete="email" class="block w-full rounded-md border border-zinc-300 px-3 py-2 text-sm outline-none transition focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100">
                                @error('email')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="password" class="mb-1 block text-sm font-medium">Senha</label>
                                <input id="password" name="password" type="password" required autocomplete="current-password" class="block w-full rounded-md border border-zinc-300 px-3 py-2 text-sm outline-none transition focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100">
                                @error('password')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <label class="flex items-center gap-2 text-sm text-zinc-600">
                                <input name="remember" type="checkbox" value="1" class="size-4 rounded border-zinc-300 text-emerald-600 focus:ring-emerald-500">
                                Manter conectado
                            </label>

                            <button type="submit" class="inline-flex w-full items-center justify-center gap-2 rounded-md bg-emerald-600 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-emerald-700">
                                Entrar
                            </button>
                        </form>
                    </div>
                </div>
            </section>
        </main>
    </body>
</html>
