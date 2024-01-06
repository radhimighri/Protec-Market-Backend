<?php

namespace Tests\Feature\Api;

use App\Models\User;
use App\Models\Order;
use App\Models\Product;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProductOrdersTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(\Database\Seeders\PermissionsSeeder::class);

        $this->withoutExceptionHandling();
    }

    /**
     * @test
     */
    public function it_gets_product_orders()
    {
        $product = Product::factory()->create();
        $order = Order::factory()->create();

        $product->order()->attach($order);

        $response = $this->getJson(
            route('api.products.orders.index', $product)
        );

        $response->assertOk()->assertSee($order->number);
    }

    /**
     * @test
     */
    public function it_can_attach_orders_to_product()
    {
        $product = Product::factory()->create();
        $order = Order::factory()->create();

        $response = $this->postJson(
            route('api.products.orders.store', [$product, $order])
        );

        $response->assertNoContent();

        $this->assertTrue(
            $product
                ->order()
                ->where('orders.id', $order->id)
                ->exists()
        );
    }

    /**
     * @test
     */
    public function it_can_detach_orders_from_product()
    {
        $product = Product::factory()->create();
        $order = Order::factory()->create();

        $response = $this->deleteJson(
            route('api.products.orders.store', [$product, $order])
        );

        $response->assertNoContent();

        $this->assertFalse(
            $product
                ->order()
                ->where('orders.id', $order->id)
                ->exists()
        );
    }
}
