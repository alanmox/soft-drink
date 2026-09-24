<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\Sale;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_the_dashboard_renders_when_empty(): void
    {
        $this->get(route('dashboard'))
            ->assertOk()
            ->assertSee('No sales yet.')
            ->assertSee('All drinks are well stocked.');
    }

    public function test_it_totals_only_todays_sales(): void
    {
        $product = Product::factory()->create();
        Sale::factory()->create(['product_id' => $product->id, 'quantity' => 1, 'total_price' => 30]);
        Sale::factory()->create(['product_id' => $product->id, 'quantity' => 1, 'total_price' => 12.50]);

        $old = Sale::factory()->create(['product_id' => $product->id, 'quantity' => 1, 'total_price' => 999]);
        $old->forceFill(['created_at' => now()->subDays(2)])->save();

        $this->get(route('dashboard'))
            ->assertOk()
            ->assertSee('42.50')
            ->assertDontSee('1,041.50');
    }

    public function test_it_lists_only_low_stock_products(): void
    {
        Product::factory()->create(['name' => 'Nearly Gone', 'stock_quantity' => Product::LOW_STOCK_THRESHOLD]);
        Product::factory()->create(['name' => 'Plenty Left', 'stock_quantity' => Product::LOW_STOCK_THRESHOLD + 1]);

        $this->get(route('dashboard'))
            ->assertOk()
            ->assertSee('Nearly Gone')
            ->assertDontSee('Plenty Left');
    }
}
