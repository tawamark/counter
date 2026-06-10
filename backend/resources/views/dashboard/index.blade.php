<x-layouts.app title="Dashboard">
    <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
        <section class="rounded-lg border border-zinc-200 bg-white p-4 shadow-sm">
            <div class="flex items-center justify-between">
                <p class="text-sm font-medium text-zinc-500">Produtos</p>
                <i data-lucide="package" class="size-5 text-emerald-600"></i>
            </div>
            <p class="mt-3 text-2xl font-semibold">0</p>
        </section>

        <section class="rounded-lg border border-zinc-200 bg-white p-4 shadow-sm">
            <div class="flex items-center justify-between">
                <p class="text-sm font-medium text-zinc-500">Categorias</p>
                <i data-lucide="tags" class="size-5 text-emerald-600"></i>
            </div>
            <p class="mt-3 text-2xl font-semibold">0</p>
        </section>

        <section class="rounded-lg border border-zinc-200 bg-white p-4 shadow-sm">
            <div class="flex items-center justify-between">
                <p class="text-sm font-medium text-zinc-500">Fornecedores</p>
                <i data-lucide="truck" class="size-5 text-emerald-600"></i>
            </div>
            <p class="mt-3 text-2xl font-semibold">0</p>
        </section>

        <section class="rounded-lg border border-zinc-200 bg-white p-4 shadow-sm">
            <div class="flex items-center justify-between">
                <p class="text-sm font-medium text-zinc-500">Contagens abertas</p>
                <i data-lucide="clipboard-list" class="size-5 text-emerald-600"></i>
            </div>
            <p class="mt-3 text-2xl font-semibold">0</p>
        </section>
    </div>

    <section class="mt-6 rounded-lg border border-zinc-200 bg-white p-6 shadow-sm">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-lg font-semibold">Movimentações recentes</h2>
                <p class="mt-1 text-sm text-zinc-500">As últimas operações de estoque aparecerão aqui.</p>
            </div>
            <i data-lucide="arrow-down-up" class="size-5 text-zinc-400"></i>
        </div>

        <div class="mt-6 flex min-h-40 items-center justify-center rounded-md border border-dashed border-zinc-300">
            <p class="text-sm text-zinc-500">Nenhuma movimentação registrada.</p>
        </div>
    </section>
</x-layouts.app>
