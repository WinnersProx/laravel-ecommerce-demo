<?php

namespace Tests\Feature\Order;

use App\Models\Product;
use App\Models\ProductOrder;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class OrderInitTest extends TestCase
{
    private Product $product;

    private User $user;

    public function setUp(): void
    {
        parent::setUp();

        $this->product = Product::factory()->create([
            'quantity' => 10
        ]);

        $this->user = User::factory()->create();
    }

    /**
     *
     * ORDER INITIALISATION
     * A user cannot initiate an order without them being logged in
     */
    public function test_guest_user_cannot_initiate_an_order()
    {
        $response = $this->post('/order/init-product-order', []);

        $response->assertStatus(401);
    }

    // Test a user cannot request more than the existing quantity
    public function test_user_cannot_request_higher_quantity_on_a_product()
    {
        $response = $this->withoutExceptionHandling()
            ->post('/order/init-product-order', [
                'product_id' => $this->product->id,
                'quantity' => 11
            ]);

        $response->assertJson([
            'success' => false,
            'message' => "The quantity should be less than {$this->product->quantity}"
        ]);

        $response->assertStatus(400);
    }

    public function test_user_can_initiate_an_order()
    {
        // TODO: This test should be able to run independently (changing its position)
        $response = $this->actingAs($this->user, 'api')
            ->post('/order/init-product-order', [
                'product_id' => $this->product->id,
                'quantity' => 2
            ]);

        $response->assertStatus(200);

        $this->assertTrue($response['success']);

        $response->assertJson(fn (AssertableJson $json) => ($json->has('order')));

        $this->assertEquals(9, $this->product->quantity);
    }


    /**
     * ORDER COMPLETION
     * Cannot complete an order if the user requesting ain't the the owner
     */
    public function test_user_cannot_complete_some_other_user_order()
    {
        /**
         * @var User $newUser
         */
        $newUser = User::factory()->create();

        $order = ProductOrder::factory()->forUser($this->user)->create();

        $response = $this->actingAs($newUser, 'api')
            ->patch("/order/complete-order/{$order->id}");

        $response->assertStatus(403);

        $this->assertTrue($response['success']);
    }


    // Set the order as completed, could also check if the payment is performed after the payment is done
    public function test_user_can_complete_an_their_initiated_order()
    {
        $order = ProductOrder::factory()->forUser($this->user)->create();

        $response = $this->actingAs($this->user, 'api')
            ->patch("/order/complete-order/{$order->id}");

        $response->assertStatus(200);

        $this->assertTrue($response['success']);

        $this->assertTrue($order->status === 'completed');
    }
}
