<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Entrar | {{ config('app.name', 'Counter') }}</title>
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="min-h-screen bg-counter-bg text-counter-text antialiased">
        <main class="grid min-h-screen lg:grid-cols-[1fr_440px]">
            <section class="hidden bg-counter-text px-10 py-12 text-white lg:flex lg:flex-col lg:justify-between">
                <img src="{{ asset('images/logo.svg') }}" alt="Counter" class="h-14 w-auto">

                <div class="max-w-xl">
                    <p class="text-sm font-medium text-counter-primary">Sistema web e mobile</p>
                    <h1 class="mt-4 text-4xl font-semibold tracking-normal">Gerencie produtos, movimentações e contagens físicas em um único fluxo.</h1>
                    <p class="mt-4 max-w-lg text-base text-white/75">Acesse o painel administrativo para acompanhar o estoque, registrar operações e analisar divergências.</p>
                </div>
            </section>

            <section class="flex items-center justify-center px-4 py-10 sm:px-6">
                <div class="w-full max-w-sm">
                    <div class="mb-8 lg:hidden">
                        <img src="{{ asset('images/logo.svg') }}" alt="Counter" class="h-14 w-auto">
                    </div>

                    <div class="rounded-lg border border-[#e5e0dc] bg-counter-bg p-6 shadow-sm">
                        <div class="mb-6">
                            <h2 class="text-xl font-semibold">Entrar</h2>
                            <p class="mt-1 text-sm text-[#6f6f6f]">Informe suas credenciais para acessar o sistema.</p>
                        </div>

                        <form method="POST" action="{{ route('login.store') }}" class="space-y-4">
                            @csrf

                            <div>
                                <label for="email" class="mb-1 block text-sm font-medium">E-mail</label>
                                <input id="email" name="email" type="email" value="{{ old('email') }}" required autofocus autocomplete="email" class="block w-full rounded-md border border-[#d8d2cc] px-3 py-2 text-sm outline-none transition focus:border-counter-primary focus:ring-2 focus:ring-orange-100">
                                @error('email')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="password" class="mb-1 block text-sm font-medium">Senha</label>
                                <input id="password" name="password" type="password" required autocomplete="current-password" class="block w-full rounded-md border border-[#d8d2cc] px-3 py-2 text-sm outline-none transition focus:border-counter-primary focus:ring-2 focus:ring-orange-100">
                                @error('password')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <label class="flex items-center gap-2 text-sm text-[#6f6f6f]">
                                <input name="remember" type="checkbox" value="1" class="size-4 rounded border-[#d8d2cc] text-counter-primary focus:ring-counter-primary">
                                Manter conectado
                            </label>

                            <button type="submit" class="inline-flex w-full items-center justify-center gap-2 rounded-md bg-counter-primary px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-[#e85f16]">
                                Entrar
                            </button>
                        </form>
                    </div>
                </div>
            </section>
        </main>
    </body>
</html>
