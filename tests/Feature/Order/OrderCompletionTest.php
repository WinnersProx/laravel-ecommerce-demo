<?php

namespace Tests\Feature\Order;

use App\Models\Product;
use App\Models\ProductOrder;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class OrderCompletionTest extends TestCase
{
    private Product $product;

    private User $user;

    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();

        $this->withHeaders(['accept' => 'application/json']);

        $this->product = Product::factory()->for($this->user, 'owner')
            ->create();
    }


    public function test_user_cannot_complete_a_non_existing_order()
    {
        $response = $this->actingAs($this->user, 'api')
            ->patch("/api/order/complete-order/2000");

        $response->assertStatus(404);

        $this->assertFalse($response['success']);
    }

    /**
     * Cannot complete an order if the user requesting ain't the the owner
     */
    public function test_user_cannot_complete_some_other_user_order()
    {
        /**
         * @var User $newUser
         */
        $newUser = User::factory()->create();

        $order = ProductOrder::factory()
            ->for($this->user, 'user')->for($this->product)
            ->create();

        $response = $this->actingAs($newUser, 'api')
            ->patch("/api/order/complete-order/{$order->id}");

        $response->assertStatus(403);

        $this->assertFalse($response['success']);
    }


    // Set the order as completed, could also check if the payment is performed after the payment is done
    public function test_user_can_complete_their_initiated_order()
    {
        $order = ProductOrder::factory()
            ->for($this->user)
            ->for($this->product)
            ->create();

        $response = $this->actingAs($this->user, 'api')
            ->patch("/api/order/complete-order/{$order->id}");

        $response->assertStatus(200);

        $this->assertTrue($response['success']);

        $this->assertTrue($order->fresh()->status === 'completed');
    }
}
