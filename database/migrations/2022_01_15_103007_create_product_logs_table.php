<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_logs', function (Blueprint $table) {
            $table->id();

            $table->integer('product_id');

            // payload -> order_id, quantity, unit_price, total_price
            $table->string('order_payload')->nullable();

            // The payload for product mutations -> previous_price, new_price, previous_quantity, new_quantity
            $table->string('product_payload')->nullable();

            $table->string('event_type')->description('The type of event which occured');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('product_logs');
    }
}
