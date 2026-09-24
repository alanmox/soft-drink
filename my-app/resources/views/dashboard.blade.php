@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <h1 class="text-2xl font-semibold">Dashboard</h1>

    <div class="grid gap-6 md:grid-cols-2">
        <section class="rounded-lg border border-stone-200 bg-white p-5">
            <h2 class="text-sm font-medium text-stone-500">Total sales today</h2>
            <p class="mt-2 text-3xl font-semibold">{{ number_format($todayTotal, 2) }}</p>
        </section>

        <section class="rounded-lg border border-stone-200 bg-white p-5">
            <h2 class="text-sm font-medium text-stone-500">Low stock alerts</h2>
            @forelse ($lowStock as $product)
                <p class="mt-2 flex justify-between text-sm">
                    <span>{{ $product->name }} <span class="text-stone-500">{{ $product->brand }} · {{ $product->size }}</span></span>
                    <span class="font-medium text-red-700">{{ $product->stock_quantity }} left</span>
                </p>
            @empty
                <p class="mt-2 text-sm text-stone-500">All drinks are well stocked.</p>
            @endforelse
        </section>
    </div>

    <section class="rounded-lg border border-stone-200 bg-white p-5">
        <h2 class="mb-3 text-sm font-medium text-stone-500">Recent transactions</h2>
        @include('partials.recent-sales', ['sales' => $recentSales])
    </section>
@endsection
