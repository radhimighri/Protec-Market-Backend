<?php

namespace Tests\Feature\Controllers;

use App\Models\User;
use App\Models\Review;

use App\Models\Product;
use App\Models\Customer;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ReviewControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected function setUp(): void
    {
        parent::setUp();

        $this->actingAs(
            User::factory()->create(['email' => 'admin@admin.com'])
        );

        $this->seed(\Database\Seeders\PermissionsSeeder::class);

        $this->withoutExceptionHandling();
    }

    /**
     * @test
     */
    public function it_displays_index_view_with_reviews()
    {
        $reviews = Review::factory()
            ->count(5)
            ->create();

        $response = $this->get(route('reviews.index'));

        $response
            ->assertOk()
            ->assertViewIs('app.reviews.index')
            ->assertViewHas('reviews');
    }

    /**
     * @test
     */
    public function it_displays_create_view_for_review()
    {
        $response = $this->get(route('reviews.create'));

        $response->assertOk()->assertViewIs('app.reviews.create');
    }

    /**
     * @test
     */
    public function it_stores_the_review()
    {
        $data = Review::factory()
            ->make()
            ->toArray();

        $response = $this->post(route('reviews.store'), $data);

        unset($data['customer_id']);

        $this->assertDatabaseHas('reviews', $data);

        $review = Review::latest('id')->first();

        $response->assertRedirect(route('reviews.edit', $review));
    }

    /**
     * @test
     */
    public function it_displays_show_view_for_review()
    {
        $review = Review::factory()->create();

        $response = $this->get(route('reviews.show', $review));

        $response
            ->assertOk()
            ->assertViewIs('app.reviews.show')
            ->assertViewHas('review');
    }

    /**
     * @test
     */
    public function it_displays_edit_view_for_review()
    {
        $review = Review::factory()->create();

        $response = $this->get(route('reviews.edit', $review));

        $response
            ->assertOk()
            ->assertViewIs('app.reviews.edit')
            ->assertViewHas('review');
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

        $response = $this->put(route('reviews.update', $review), $data);

        unset($data['customer_id']);

        $data['id'] = $review->id;

        $this->assertDatabaseHas('reviews', $data);

        $response->assertRedirect(route('reviews.edit', $review));
    }

    /**
     * @test
     */
    public function it_deletes_the_review()
    {
        $review = Review::factory()->create();

        $response = $this->delete(route('reviews.destroy', $review));

        $response->assertRedirect(route('reviews.index'));

        $this->assertModelMissing($review);
    }
}
