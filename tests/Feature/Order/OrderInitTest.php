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

    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();

        $this->withHeaders(['accept' => 'application/json']);

        $this->product = Product::factory()->for($this->user, 'owner')
            ->create();
    }

    /**
     *
     * ORDER INITIALISATION
     * A user cannot initiate an order without them being logged in
     */
    public function test_guest_user_cannot_initiate_an_order()
    {
        $response = $this->post('/api/order/init-product-order', []);

        $response->assertStatus(401);
    }

    /**
     * Cannot initialize an order from a non existing product
     */
    public function test_user_cannot_initialize_an_order_from_a_non_existing_product()
    {
        $response = $this->actingAs($this->user, 'api')
        ->post('/api/order/init-product-order', [
            'product_id' => 200,
            'quantity' => 11
        ]);

        $response->assertJsonValidationErrors('product_id');

        $response->assertStatus(422);
    }

    // Test a user cannot request more than the existing quantity
    public function test_user_cannot_request_higher_quantity_on_a_product()
    {
        $productQuantity = 10;

        $newProduct = Product::factory()->state(['quantity' => $productQuantity])
            ->for($this->user, 'owner')
            ->create();

        $response = $this->actingAs($this->user, 'api')
        ->post('/api/order/init-product-order', [
            'product_id' => $newProduct->id,
            'quantity' => 12
            ]);

        $response->assertJson([
            'success' => false,
            'message' => "The quantity should be less than or equal to {$productQuantity}"
        ]);

        $response->assertStatus(400);
    }

    public function test_user_can_initiate_an_order()
    {
        $initialQuantity = $this->product->quantity;

        $newProduct = Product::factory()->state(['quantity' => $initialQuantity])
            ->for($this->user, 'owner')
            ->create();


        $response = $this->actingAs($this->user, 'api')
            ->post('/api/order/init-product-order', [
                'product_id' => $newProduct->id,
                'quantity' => 2
            ]);

        $response->assertStatus(200);

        $this->assertTrue($response['success']);

        $response->assertJsonStructure(['success', 'order']);

        $this->assertEquals(($initialQuantity - 2), $newProduct->fresh()->quantity);
    }
}
