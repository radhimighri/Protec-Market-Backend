<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('customer_id');
            $table->decimal('total_price')->nullable();
            $table->enum('stauts', [
                'pending',
                'processing',
                'packed',
                'picked',
                'cancelled',
            ]);
            $table->string('number')->unique();
            $table->enum('payment_method', [
                'stripe',
                'paypal',
                'pay on site',
                'vr pay'
            ]);
            $table->enum('payment_status', [
                'paid',
                'not paid',
            ]);
            $table->datetime('pick_up_date');
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
};
