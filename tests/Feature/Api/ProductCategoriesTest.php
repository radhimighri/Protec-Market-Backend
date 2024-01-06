<?php

namespace Tests\Feature\Api;

use App\Models\User;
use App\Models\Product;
use App\Models\Categorie;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProductCategoriesTest extends TestCase
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
    public function it_gets_product_categories()
    {
        $product = Product::factory()->create();
        $categorie = Categorie::factory()->create();

        $product->categories()->attach($categorie);

        $response = $this->getJson(
            route('api.products.categories.index', $product)
        );

        $response->assertOk()->assertSee($categorie->name);
    }

    /**
     * @test
     */
    public function it_can_attach_categories_to_product()
    {
        $product = Product::factory()->create();
        $categorie = Categorie::factory()->create();

        $response = $this->postJson(
            route('api.products.categories.store', [$product, $categorie])
        );

        $response->assertNoContent();

        $this->assertTrue(
            $product
                ->categories()
                ->where('categories.id', $categorie->id)
                ->exists()
        );
    }

    /**
     * @test
     */
    public function it_can_detach_categories_from_product()
    {
        $product = Product::factory()->create();
        $categorie = Categorie::factory()->create();

        $response = $this->deleteJson(
            route('api.products.categories.store', [$product, $categorie])
        );

        $response->assertNoContent();

        $this->assertFalse(
            $product
                ->categories()
                ->where('categories.id', $categorie->id)
                ->exists()
        );
    }
}
