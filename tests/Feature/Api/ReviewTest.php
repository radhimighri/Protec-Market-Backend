<?php

namespace Tests\Feature\Api;

use App\Models\User;
use App\Models\Review;

use App\Models\Product;
use App\Models\Customer;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ReviewTest extends TestCase
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
    public function it_gets_reviews_list()
    {
        $reviews = Review::factory()
            ->count(5)
            ->create();

        $response = $this->getJson(route('api.reviews.index'));

        $response->assertOk()->assertSee($reviews[0]->id);
    }

    /**
     * @test
     */
    public function it_stores_the_review()
    {
        $data = Review::factory()
            ->make()
            ->toArray();

        $response = $this->postJson(route('api.reviews.store'), $data);

        unset($data['customer_id']);

        $this->assertDatabaseHas('reviews', $data);

        $response->assertStatus(201)->assertJsonFragment($data);
    }

    /**
     * @test
     */
    public function it_updates_the_review()
    {
        $review = Review::factory()->create();

        $product = Product::factory()->create();
        $customer = Customer::factory()->create();

        $data = [
            'review' => $this->faker->text,
            'product_id' => $product->id,
            'customer_id' => $customer->id,
        ];

        $response = $this->putJson(route('api.reviews.update', $review), $data);

        unset($data['customer_id']);

        $data['id'] = $review->id;

        $this->assertDatabaseHas('reviews', $data);

        $response->assertOk()->assertJsonFragment($data);
    }

    /**
     * @test
     */
    public function it_deletes_the_review()
    {
        $review = Review::factory()->create();

        $response = $this->deleteJson(route('api.reviews.destroy', $review));

        $this->assertModelMissing($review);

        $response->assertNoContent();
    }
}
