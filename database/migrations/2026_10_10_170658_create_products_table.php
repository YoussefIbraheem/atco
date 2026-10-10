<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string("sku");
            $table->string("name");
            $table->string("slug")->unique();
            $table->decimal("buying_price",8,2);
            $table->decimal("selling_price",8,2);
            $table->decimal("wholesale_price",8,2);
            $table->integer("total_amount");
            $table->foreignId("provider_id");
            $table->foreignId("type_id");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
