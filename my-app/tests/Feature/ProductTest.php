<?php

namespace Tests\Feature;

use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductTest extends TestCase
{
    use RefreshDatabase;

    public function test_the_inventory_page_lists_products(): void
    {
        Product::factory()->create(['name' => 'Mango Fizz']);

        $this->get(route('products.index'))->assertOk()->assertSee('Mango Fizz');
    }

    public function test_a_product_can_be_added(): void
    {
        $this->post(route('products.store'), [
            'name' => 'Cola',
            'brand' => 'Acme',
            'size' => '500ml',
            'stock_quantity' => 24,
            'price' => '1.99',
        ])->assertRedirect(route('products.index'));

        $this->assertDatabaseHas('products', ['name' => 'Cola', 'stock_quantity' => 24, 'price' => '1.99']);
    }

    public function test_adding_a_product_validates_required_fields(): void
    {
        $this->post(route('products.store'), [])
            ->assertSessionHasErrors(['name', 'brand', 'size', 'stock_quantity', 'price']);

        $this->assertSame(0, Product::count());
    }

    public function test_restocking_adds_to_the_current_stock(): void
    {
        $product = Product::factory()->create(['stock_quantity' => 5]);

        $this->patch(route('products.restock', $product), ['quantity' => 12])
            ->assertRedirect(route('products.index'));

        $this->assertSame(17, $product->fresh()->stock_quantity);
    }

    public function test_restocking_requires_a_positive_quantity(): void
    {
        $product = Product::factory()->create(['stock_quantity' => 5]);

        $this->patch(route('products.restock', $product), ['quantity' => 0])
            ->assertSessionHasErrors('quantity');

        $this->assertSame(5, $product->fresh()->stock_quantity);
    }
}
