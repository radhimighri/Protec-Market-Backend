<?php

namespace Tests\Feature\Api;

use App\Models\User;
use App\Models\Categorie;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CategorieTest extends TestCase
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
    public function it_gets_categories_list()
    {
        $categories = Categorie::factory()
            ->count(5)
            ->create();

        $response = $this->getJson(route('api.categories.index'));

        $response->assertOk()->assertSee($categories[0]->name);
    }

    /**
     * @test
     */
    public function it_stores_the_categorie()
    {
        $data = Categorie::factory()
            ->make()
            ->toArray();

        $response = $this->postJson(route('api.categories.store'), $data);

        $this->assertDatabaseHas('categories', $data);

        $response->assertStatus(201)->assertJsonFragment($data);
    }

    /**
     * @test
     */
    public function it_updates_the_categorie()
    {
        $categorie = Categorie::factory()->create();

        $data = [
            'name' => $this->faker->name,
            'logo' => $this->faker->text(255),
        ];

        $response = $this->putJson(
            route('api.categories.update', $categorie),
            $data
        );

        $data['id'] = $categorie->id;

        $this->assertDatabaseHas('categories', $data);

        $response->assertOk()->assertJsonFragment($data);
    }

    /**
     * @test
     */
    public function it_deletes_the_categorie()
    {
        $categorie = Categorie::factory()->create();

        $response = $this->deleteJson(
            route('api.categories.destroy', $categorie)
        );

        $this->assertModelMissing($categorie);

        $response->assertNoContent();
    }
}
