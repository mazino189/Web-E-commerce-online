<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// this is migration of  order table //
return new class extends Migration {
    // run the migration //
    public function up(): void
    {
        // create orders table //
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->decimal('total_amount', 10, 2);
            $table->string('status')->default('pending');
            $table->string('shipping_address');
            $table->string('phone_number');
            $table->string('payment_status')->default('unpaid');
            $table->string('payment_method')->default('COD');
            $table->timestamps();
        });
    }
    // reverse the migration //
    public function down():void
    {
        // drop orders table //
        Schema::dropIfExists('orders');
    }
};