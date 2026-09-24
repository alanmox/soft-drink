<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\Sale;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SaleTest extends TestCase
{
    use RefreshDatabase;

    public function test_a_sale_is_recorded_and_reduces_stock(): void
    {
        $product = Product::factory()->create(['stock_quantity' => 20, 'price' => 2.50]);

        $this->post(route('sales.store'), ['product_id' => $product->id, 'quantity' => 4])
            ->assertRedirect(route('sales.index'))
            ->assertSessionHas('status');

        $this->assertDatabaseHas('sales', [
            'product_id' => $product->id,
            'quantity' => 4,
            'total_price' => '10.00',
        ]);
        $this->assertSame(16, $product->fresh()->stock_quantity);
    }

    public function test_a_sale_for_more_than_the_stock_is_rejected(): void
    {
        $product = Product::factory()->create(['stock_quantity' => 3]);

        $this->post(route('sales.store'), ['product_id' => $product->id, 'quantity' => 4])
            ->assertSessionHasErrors('quantity');

        $this->assertSame(0, Sale::count());
        $this->assertSame(3, $product->fresh()->stock_quantity);
    }

    public function test_a_sale_can_sell_the_remaining_stock_exactly(): void
    {
        $product = Product::factory()->create(['stock_quantity' => 3]);

        $this->post(route('sales.store'), ['product_id' => $product->id, 'quantity' => 3])
            ->assertSessionHasNoErrors();

        $this->assertSame(0, $product->fresh()->stock_quantity);
    }

    public function test_a_sale_validates_its_input(): void
    {
        $this->post(route('sales.store'), ['product_id' => 999, 'quantity' => 0])
            ->assertSessionHasErrors(['product_id', 'quantity']);
    }

    public function test_the_sales_page_only_offers_drinks_in_stock(): void
    {
        Product::factory()->create(['name' => 'Available Cola', 'stock_quantity' => 5]);
        Product::factory()->create(['name' => 'Sold Out Fizz', 'stock_quantity' => 0]);

        $this->get(route('sales.index'))
            ->assertOk()
            ->assertSee('Available Cola')
            ->assertDontSee('Sold Out Fizz');
    }
}
