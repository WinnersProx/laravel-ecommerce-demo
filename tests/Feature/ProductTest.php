<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class ProductTest extends TestCase
{

    use RefreshDatabase;

    private User $user;


    public function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
    }

    public function test_guest_user_cannot_create_a_product()
    {
        $response = $this->withHeaders(['Accept' => 'application/json'])->post('/api/products', [
            'name' => 'Channel Bag',
            'description' => 'Awesome bag',
            'price' => 50000,
            'quantity' => 20
        ]);

        $response->assertStatus(401);
    }

    public function test_user_must_provide_required_fields_to_create_a_product()
    {
        $this->actingAs($this->user, 'api');

        $response = $this->withHeaders(['Accept' => 'application/json'])->post('/api/products', []);

        $response
            ->assertStatus(422)
            ->assertJson(fn (AssertableJson $json) => (
                ($json->has('message')->has('errors')->has('errors.name'))
            ));
    }

    public function test_user_can_create_product()
    {
        $this->actingAs($this->user, 'api');

        $response = $this->withHeaders(['Accept' => 'application/json'])->post('/api/products', [
            'name' => 'Channel Bag',
            'description' => 'Awesome bag',
            'price' => 50000,
            'quantity' => 20
        ]);

        $response
            ->assertStatus(201)
            ->assertJson(fn (AssertableJson $json) => (
                ($json->has('success')->has('message')->has('product'))
            ));
    }
}
