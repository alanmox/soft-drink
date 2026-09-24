@if ($sales->isEmpty())
    <p class="text-sm text-stone-500">No sales yet.</p>
@else
    <table class="w-full text-left text-sm">
        <thead class="text-stone-500">
            <tr>
                <th class="py-2 font-medium">Date</th>
                <th class="py-2 font-medium">Drink</th>
                <th class="py-2 text-right font-medium">Qty</th>
                <th class="py-2 text-right font-medium">Total</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-stone-100">
            @foreach ($sales as $sale)
                <tr>
                    <td class="py-2">{{ $sale->created_at->format('M j, H:i') }}</td>
                    <td class="py-2">{{ $sale->product->name }} <span class="text-stone-500">{{ $sale->product->size }}</span></td>
                    <td class="py-2 text-right">{{ $sale->quantity }}</td>
                    <td class="py-2 text-right">{{ number_format($sale->total_price, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endif
