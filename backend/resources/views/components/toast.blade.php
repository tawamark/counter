@props([
    'message',
    'type' => 'success',
])

@php
    $isError = $type === 'error';
@endphp

<div x-data="{ show: true }" x-init="setTimeout(() => show = false, 4500)" x-show="show" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="translate-y-2 opacity-0" x-transition:enter-end="translate-y-0 opacity-100" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="translate-y-0 opacity-100" x-transition:leave-end="translate-y-2 opacity-0" class="fixed right-4 top-4 z-50 w-[calc(100vw-2rem)] max-w-sm rounded-lg border bg-counter-bg p-4 shadow-lg {{ $isError ? 'border-red-200' : 'border-green-200' }}" role="status" aria-live="polite">
    <div class="flex gap-3">
        <div class="mt-0.5 flex size-8 shrink-0 items-center justify-center rounded-full {{ $isError ? 'bg-red-50 text-red-600' : 'bg-green-50 text-green-700' }}">
            <i data-lucide="{{ $isError ? 'alert-triangle' : 'circle-check' }}" class="size-4"></i>
        </div>
        <div class="min-w-0 flex-1">
            <p class="text-sm font-semibold">{{ $isError ? 'Atenção' : 'Tudo certo' }}</p>
            <p class="mt-1 text-sm text-[#6f6f6f]">{{ $message }}</p>
        </div>
        <button type="button" x-on:click="show = false" class="inline-flex size-8 shrink-0 items-center justify-center rounded-md text-[#6f6f6f] transition hover:bg-[#f7f5f3] hover:text-counter-primary" aria-label="Fechar aviso">
            <i data-lucide="x" class="size-4"></i>
        </button>
    </div>
</div>
