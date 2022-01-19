<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ProductTest extends TestCase
{
    private User $user;

    public function __construct()
    {
        $this->user = User::find(1);
    }

    public function test_create_product()
    {
        $this->actingAs($this->user);

        $response = $this->post('/api/products', []);

        $response->assertStatus(200);
    }
}
