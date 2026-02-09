<?php

use App\Models\ProductTitle;
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
        //
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->integer('quantity')->default(0);
            $table->integer('unit_price')->nullable();
            $table->integer('cost')->nullable();
            $table->year('publishing_year')->nullable();
            $table->integer('capacity')->nullable();
            $table->string('color', 50)->nullable();
            $table->foreignIdFor(ProductTitle::class, 'product_title_id')->constrained()->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
        Schema::dropIfExists('products');
    }
};