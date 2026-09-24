<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Sale;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class SaleController extends Controller
{
    public function index(): View
    {
        return view('sales.index', [
            'products' => Product::where('stock_quantity', '>', 0)->orderBy('name')->get(),
            'recentSales' => Sale::with('product')->latest()->limit(10)->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'product_id' => ['required', 'exists:products,id'],
            'quantity' => ['required', 'integer', 'min:1'],
        ]);

        $sale = DB::transaction(function () use ($data) {
            $product = Product::lockForUpdate()->findOrFail($data['product_id']);

            if ($product->stock_quantity < $data['quantity']) {
                throw ValidationException::withMessages([
                    'quantity' => "Only {$product->stock_quantity} in stock for {$product->name}.",
                ]);
            }

            $sale = Sale::create([
                'product_id' => $product->id,
                'quantity' => $data['quantity'],
                'total_price' => $product->price * $data['quantity'],
            ]);

            $product->decrement('stock_quantity', $data['quantity']);

            return $sale;
        });

        return redirect()->route('sales.index')
            ->with('status', "Sold {$sale->quantity} × {$sale->product->name} for {$sale->total_price}.");
    }
}
