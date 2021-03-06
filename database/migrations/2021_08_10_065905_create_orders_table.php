<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('client_id');
            $table->string('order_id');
            $table->string('order_serial_id');
            $table->longText('products')->nullable();
            $table->longText('customer')->nullable();
            $table->longText('status')->default(0);
            $table->string('invoice_type')->default('slip');
            $table->string('discount')->default(0);
            $table->longText('total_amount')->default(0);
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
        Schema::dropIfExists('orders');
    }
}
