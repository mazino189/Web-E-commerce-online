<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

//this is migration carts table //
return new class extends Migration {
    // run the migration //
    public function up(): void
    {
        // create carts table //
        Schema::create('carts', function (Blueprint $table) {
              $table->id();

              $table->foreignId('user_id')
              ->constrained()
              ->onDelete('cascade');

              $table->foreignId('product_id')
              ->constrained()
              ->onDelete('cascade');
              
              $table->integer('quantity')->default(1);
              $table->string('status')->default('active');
              $table->timestamps();
        });
    }
    // reverse the migration //
    public function down(): void
    {        // drop carts table //
        Schema::dropIfExists('carts');
    }
};