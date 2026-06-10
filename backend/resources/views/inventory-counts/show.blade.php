<x-layouts.app :title="$count->title">
    <div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between">
        <div>
            <a href="{{ route('inventory-counts.index') }}" class="inline-flex items-center gap-2 text-sm font-medium text-counter-primary">
                <i data-lucide="chevron-down" class="size-4 rotate-90"></i>
                Voltar para contagens
            </a>
            <h1 class="mt-3 text-2xl font-semibold">{{ $count->title }}</h1>
            <p class="mt-1 text-sm text-[#6f6f6f]">Saldo congelado em {{ $count->started_at?->format('d/m/Y H:i') ?? '-' }}.</p>
        </div>
        <span class="inline-flex w-fit rounded-full bg-orange-50 px-3 py-1 text-sm font-medium text-counter-primary">
            {{ ['open' => 'Aberta', 'finished' => 'Finalizada', 'approved' => 'Aprovada'][$count->status] ?? $count->status }}
        </span>
    </div>

    @if (session('status'))
        <div class="mb-4 rounded-md border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">
            {{ session('status') }}
        </div>
    @endif

    <section class="mb-4 grid gap-4 md:grid-cols-3">
        <div class="rounded-lg border border-[#e5e0dc] bg-counter-bg p-4 shadow-sm">
            <p class="text-sm text-[#6f6f6f]">Produtos</p>
            <p class="mt-1 text-2xl font-semibold">{{ $count->items->count() }}</p>
        </div>
        <div class="rounded-lg border border-[#e5e0dc] bg-counter-bg p-4 shadow-sm">
            <p class="text-sm text-[#6f6f6f]">Criada por</p>
            <p class="mt-1 text-lg font-semibold">{{ $count->creator?->name ?? 'Usuário removido' }}</p>
        </div>
        <div class="rounded-lg border border-[#e5e0dc] bg-counter-bg p-4 shadow-sm">
            <p class="text-sm text-[#6f6f6f]">Status</p>
            <p class="mt-1 text-lg font-semibold">{{ ['open' => 'Aberta', 'finished' => 'Finalizada', 'approved' => 'Aprovada'][$count->status] ?? $count->status }}</p>
        </div>
    </section>

    <section class="rounded-lg border border-[#e5e0dc] bg-counter-bg shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full min-w-[780px] text-left text-sm">
                <thead class="bg-[#f7f5f3] text-xs uppercase text-[#6f6f6f]">
                    <tr>
                        <th class="px-4 py-3 font-semibold">Produto</th>
                        <th class="px-4 py-3 font-semibold">SKU</th>
                        <th class="px-4 py-3 font-semibold">Saldo do sistema</th>
                        <th class="px-4 py-3 font-semibold">Quantidade contada</th>
                        <th class="px-4 py-3 font-semibold">Diferença</th>
                        <th class="px-4 py-3 font-semibold">Sincronização</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#e5e0dc]">
                    @foreach ($count->items as $item)
                        <tr>
                            <td class="px-4 py-3 font-medium">{{ $item->product?->name ?? 'Produto removido' }}</td>
                            <td class="px-4 py-3 text-[#6f6f6f]">{{ $item->product?->sku ?? '-' }}</td>
                            <td class="px-4 py-3 text-[#6f6f6f]">{{ number_format((float) $item->system_quantity, 3, ',', '.') }}</td>
                            <td class="px-4 py-3 text-[#6f6f6f]">{{ $item->counted_quantity === null ? '-' : number_format((float) $item->counted_quantity, 3, ',', '.') }}</td>
                            <td class="px-4 py-3 text-[#6f6f6f]">{{ number_format((float) $item->difference, 3, ',', '.') }}</td>
                            <td class="px-4 py-3 text-[#6f6f6f]">{{ ['pending' => 'Pendente', 'synced' => 'Sincronizada', 'error' => 'Erro'][$item->sync_status] ?? $item->sync_status }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </section>
</x-layouts.app>
