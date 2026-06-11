<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// this is migration brands table // 
return new class extends Migration {
    // run the migration //
    public function up(): void
    {
        // create brands table //
        Schema::create('brands', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('logo')->nullable();
            $table->boolean('status')->default(true);
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }
    public function down(): void
    {
        // drop brands table
        Schema::dropIfExists('brands');
    }
};