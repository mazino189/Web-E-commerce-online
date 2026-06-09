<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
// this is migration categries table //
return new class extends Migration {
    // run the migration //
    public function up(): void
    {
        // create products table //
        Schema::create('products', function (Blueprint $table) {
           $table->id();
           $table->foreignId('category_id')->constrained()->onDelete('cascade');
           $table->foreignId('brand_id')->constrained()->onDelete('cascade');
           $table->string('slug')->unique();
           $table->string('name');
           $table->text('description');
           $table->decimal('price', 10, 2);
           $table->string('image');
           $table->integer('stock');
           $table->boolean('status')->default(true);
           $table->timestamps();
        });
    }
    // public function down(): void
    public function down(): void{
        // drop products table //
        Schema::dropIfExists('products');
    }
};