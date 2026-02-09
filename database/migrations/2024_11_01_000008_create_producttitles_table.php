<?php

use App\Models\ProductType;
use App\Models\Supplier;
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
        Schema::create('producttitles', function (Blueprint $table) {
            $table->id();
            $table->string('name', 200);
            $table->string('author', 50);
            $table->string('description', 800);
            $table->foreignIdFor(ProductType::class, 'product_type_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignIdFor(Supplier::class, 'supplier_id')->nullable()->constrained()->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('producttitles');
    }
};