@extends('layouts.app')

@section('title', 'Inventory')

@section('content')
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-semibold">Inventory</h1>
        <button type="button" data-open-dialog="add-drink"
                class="rounded-md bg-stone-900 px-4 py-2 text-sm font-medium text-white hover:bg-stone-700">
            Add New Drink
        </button>
    </div>

    <section class="overflow-x-auto rounded-lg border border-stone-200 bg-white">
        <table class="w-full text-left text-sm">
            <thead class="border-b border-stone-200 text-stone-500">
                <tr>
                    <th class="px-4 py-3 font-medium">Name</th>
                    <th class="px-4 py-3 font-medium">Brand</th>
                    <th class="px-4 py-3 font-medium">Size</th>
                    <th class="px-4 py-3 text-right font-medium">Stock</th>
                    <th class="px-4 py-3 text-right font-medium">Price</th>
                    <th class="px-4 py-3 font-medium">Restock</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-stone-100">
                @forelse ($products as $product)
                    <tr @class(['bg-red-50' => $product->stock_quantity <= \App\Models\Product::LOW_STOCK_THRESHOLD])>
                        <td class="px-4 py-3">{{ $product->name }}</td>
                        <td class="px-4 py-3">{{ $product->brand }}</td>
                        <td class="px-4 py-3">{{ $product->size }}</td>
                        <td class="px-4 py-3 text-right font-medium">{{ $product->stock_quantity }}</td>
                        <td class="px-4 py-3 text-right">{{ number_format($product->price, 2) }}</td>
                        <td class="px-4 py-3">
                            <form method="POST" action="{{ route('products.restock', $product) }}" class="flex gap-2">
                                @csrf
                                @method('PATCH')
                                <input type="number" name="quantity" min="1" required placeholder="Qty"
                                       aria-label="Restock quantity for {{ $product->name }}"
                                       class="w-20 rounded-md border border-stone-300 px-2 py-1">
                                <button class="rounded-md border border-stone-300 px-3 py-1 hover:bg-stone-100">Add</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="px-4 py-6 text-center text-stone-500">No drinks yet. Add your first one.</td></tr>
                @endforelse
            </tbody>
        </table>
    </section>

    @php($storeFields = ['name', 'brand', 'size', 'stock_quantity', 'price'])
    <dialog id="add-drink" class="m-auto w-full max-w-md rounded-lg p-0 backdrop:bg-stone-900/40"
            @if ($errors->hasAny($storeFields)) data-open-on-load @endif>
        <form method="POST" action="{{ route('products.store') }}" class="space-y-4 p-6">
            @csrf
            <h2 class="text-lg font-semibold">Add New Drink</h2>

            @if ($errors->hasAny($storeFields))
                <ul class="list-inside list-disc rounded-md bg-red-50 px-3 py-2 text-sm text-red-800">
                    @foreach ($storeFields as $field)
                        @foreach ($errors->get($field) as $message)
                            <li>{{ $message }}</li>
                        @endforeach
                    @endforeach
                </ul>
            @endif

            @foreach ([
                ['name', 'Name', 'text'],
                ['brand', 'Brand', 'text'],
                ['size', 'Size (e.g. 500ml)', 'text'],
                ['stock_quantity', 'Stock quantity', 'number'],
                ['price', 'Price', 'number'],
            ] as [$field, $label, $type])
                <label class="block text-sm">
                    <span class="font-medium">{{ $label }}</span>
                    <input type="{{ $type }}" name="{{ $field }}" value="{{ old($field) }}" required
                           @if ($field === 'stock_quantity') min="0" @endif
                           @if ($field === 'price') min="0" step="0.01" @endif
                           class="mt-1 w-full rounded-md border border-stone-300 px-3 py-2">
                </label>
            @endforeach

            <div class="flex justify-end gap-2">
                <button type="button" data-close-dialog class="rounded-md px-4 py-2 text-sm hover:bg-stone-100">Cancel</button>
                <button class="rounded-md bg-stone-900 px-4 py-2 text-sm font-medium text-white hover:bg-stone-700">Save drink</button>
            </div>
        </form>
    </dialog>
@endsection
