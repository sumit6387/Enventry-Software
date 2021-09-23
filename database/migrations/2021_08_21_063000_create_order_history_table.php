<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderHistoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_history', function (Blueprint $table) {
            $table->id();
            $table->string('order_id');
            $table->string('order_serial_id');
            $table->string('client_id');
            $table->string('customer_name');
            $table->string('customer_mobile_no')->nullable();
            $table->string('products');
            $table->string('invoice_type')->default('slip');
            $table->string('noofproduct')->default();
            $table->string('total_amount')->default(0);
            $table->string('discount')->default(0);
            $table->longText('pdf_link')->nullable();
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
        Schema::dropIfExists('order_history');
    }
}
