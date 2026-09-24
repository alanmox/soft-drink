<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Sale;
use Illuminate\Contracts\View\View;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        return view('dashboard', [
            'todayTotal' => Sale::whereDate('created_at', today())->sum('total_price'),
            'lowStock' => Product::lowStock()->orderBy('stock_quantity')->get(),
            'recentSales' => Sale::with('product')->latest()->limit(10)->get(),
        ]);
    }
}
