<?php

namespace Tests\Feature\Api;

use App\Models\User;
use App\Models\Product;
use App\Models\Categorie;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CategorieProductsTest extends TestCase
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
    public function it_gets_categorie_products()
    {
        $categorie = Categorie::factory()->create();
        $product = Product::factory()->create();

        $categorie->products()->attach($product);

        $response = $this->getJson(
            route('api.categories.products.index', $categorie)
        );

        $response->assertOk()->assertSee($product->name);
    }

    /**
     * @test
     */
    public function it_can_attach_products_to_categorie()
    {
        $categorie = Categorie::factory()->create();
        $product = Product::factory()->create();

        $response = $this->postJson(
            route('api.categories.products.store', [$categorie, $product])
        );

        $response->assertNoContent();

        $this->assertTrue(
            $categorie
                ->products()
                ->where('products.id', $product->id)
                ->exists()
        );
    }

    /**
     * @test
     */
    public function it_can_detach_products_from_categorie()
    {
        $categorie = Categorie::factory()->create();
        $product = Product::factory()->create();

        $response = $this->deleteJson(
            route('api.categories.products.store', [$categorie, $product])
        );

        $response->assertNoContent();

        $this->assertFalse(
            $categorie
                ->products()
                ->where('products.id', $product->id)
                ->exists()
        );
    }
}
