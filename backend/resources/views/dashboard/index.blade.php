<x-layouts.app title="Dashboard">
    <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
        <section class="rounded-lg border border-[#e5e0dc] bg-counter-bg p-4 shadow-sm">
            <div class="flex items-center justify-between">
                <p class="text-sm font-medium text-[#6f6f6f]">Produtos</p>
                <i data-lucide="package" class="size-5 text-counter-primary"></i>
            </div>
            <p class="mt-3 text-2xl font-semibold">{{ $totalProducts }}</p>
        </section>

        <section class="rounded-lg border border-[#e5e0dc] bg-counter-bg p-4 shadow-sm">
            <div class="flex items-center justify-between">
                <p class="text-sm font-medium text-[#6f6f6f]">Categorias</p>
                <i data-lucide="tags" class="size-5 text-counter-primary"></i>
            </div>
            <p class="mt-3 text-2xl font-semibold">{{ $totalCategories }}</p>
        </section>

        <section class="rounded-lg border border-[#e5e0dc] bg-counter-bg p-4 shadow-sm">
            <div class="flex items-center justify-between">
                <p class="text-sm font-medium text-[#6f6f6f]">Fornecedores</p>
                <i data-lucide="truck" class="size-5 text-counter-primary"></i>
            </div>
            <p class="mt-3 text-2xl font-semibold">{{ $totalSuppliers }}</p>
        </section>

        <section class="rounded-lg border border-[#e5e0dc] bg-counter-bg p-4 shadow-sm">
            <div class="flex items-center justify-between">
                <p class="text-sm font-medium text-[#6f6f6f]">Contagens abertas</p>
                <i data-lucide="clipboard-list" class="size-5 text-counter-primary"></i>
            </div>
            <p class="mt-3 text-2xl font-semibold">{{ $openInventoryCounts }}</p>
        </section>
    </div>

    <section class="mt-6 rounded-lg border border-[#e5e0dc] bg-counter-bg p-6 shadow-sm">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-lg font-semibold">Movimentações recentes</h2>
                <p class="mt-1 text-sm text-[#6f6f6f]">As últimas operações de estoque aparecerão aqui.</p>
            </div>
            <i data-lucide="arrow-down-up" class="size-5 text-counter-primary"></i>
        </div>

        @if ($recentMovements->isEmpty())
            <div class="mt-6 flex min-h-40 items-center justify-center rounded-md border border-dashed border-[#d8d2cc]">
                <p class="text-sm text-[#6f6f6f]">Nenhuma movimentação registrada.</p>
            </div>
        @else
            <div class="mt-6 overflow-hidden rounded-md border border-[#e5e0dc]">
                <table class="w-full text-left text-sm">
                    <thead class="bg-[#f7f5f3] text-xs uppercase text-[#6f6f6f]">
                        <tr>
                            <th class="px-4 py-3 font-semibold">Produto</th>
                            <th class="px-4 py-3 font-semibold">Tipo</th>
                            <th class="px-4 py-3 font-semibold">Quantidade</th>
                            <th class="px-4 py-3 font-semibold">Usuário</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#e5e0dc]">
                        @foreach ($recentMovements as $movement)
                            <tr>
                                <td class="px-4 py-3 font-medium">{{ $movement->product?->name ?? 'Produto removido' }}</td>
                                <td class="px-4 py-3 text-[#6f6f6f]">{{ ucfirst($movement->type) }}</td>
                                <td class="px-4 py-3 text-[#6f6f6f]">{{ number_format((float) $movement->quantity, 3, ',', '.') }}</td>
                                <td class="px-4 py-3 text-[#6f6f6f]">{{ $movement->user?->name ?? 'Usuário removido' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </section>
</x-layouts.app>
