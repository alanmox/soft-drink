@extends('layouts.app')

@section('title', 'Sales')

@section('content')
    <h1 class="text-2xl font-semibold">Sales</h1>

    <section class="rounded-lg border border-stone-200 bg-white p-5">
        <h2 class="mb-3 text-sm font-medium text-stone-500">Record a sale</h2>
        <form method="POST" action="{{ route('sales.store') }}" class="flex flex-wrap items-end gap-4">
            @csrf
            <label class="block flex-1 text-sm">
                <span class="font-medium">Drink</span>
                <select name="product_id" required class="mt-1 w-full rounded-md border border-stone-300 px-3 py-2">
                    <option value="">Select a drink…</option>
                    @foreach ($products as $product)
                        <option value="{{ $product->id }}" @selected(old('product_id') == $product->id)>
                            {{ $product->name }} · {{ $product->brand }} · {{ $product->size }} — {{ number_format($product->price, 2) }} ({{ $product->stock_quantity }} in stock)
                        </option>
                    @endforeach
                </select>
            </label>
            <label class="block w-28 text-sm">
                <span class="font-medium">Quantity</span>
                <input type="number" name="quantity" min="1" value="{{ old('quantity', 1) }}" required
                       class="mt-1 w-full rounded-md border border-stone-300 px-3 py-2">
            </label>
            <button class="rounded-md bg-stone-900 px-4 py-2 text-sm font-medium text-white hover:bg-stone-700">Submit Sale</button>
        </form>
    </section>

    <section class="rounded-lg border border-stone-200 bg-white p-5">
        <h2 class="mb-3 text-sm font-medium text-stone-500">Recent sales</h2>
        @include('partials.recent-sales', ['sales' => $recentSales])
    </section>
@endsection
