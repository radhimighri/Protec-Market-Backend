<?php

namespace Tests\Feature\Api;

use App\Models\User;
use App\Models\Review;
use App\Models\Customer;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CustomerReviewsTest extends TestCase
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
    public function it_gets_customer_reviews()
    {
        $customer = Customer::factory()->create();
        $reviews = Review::factory()
            ->count(2)
            ->create([
                'customer_id' => $customer->id,
            ]);

        $response = $this->getJson(
            route('api.customers.reviews.index', $customer)
        );

        $response->assertOk()->assertSee($reviews[0]->id);
    }

    /**
     * @test
     */
    public function it_stores_the_customer_reviews()
    {
        $customer = Customer::factory()->create();
        $data = Review::factory()
            ->make([
                'customer_id' => $customer->id,
            ])
            ->toArray();

        $response = $this->postJson(
            route('api.customers.reviews.store', $customer),
            $data
        );

        unset($data['customer_id']);

        $this->assertDatabaseHas('reviews', $data);

        $response->assertStatus(201)->assertJsonFragment($data);

        $review = Review::latest('id')->first();

        $this->assertEquals($customer->id, $review->customer_id);
    }
}
