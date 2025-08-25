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
            $table->foreignId('category_id')->constrained()->cascadeOnDelete(); // FK -> categories
            $table->string('name');                       // tên sản phẩm
            $table->text('description')->nullable();     // mô tả
            $table->unsignedInteger('quantity')->default(0); // số lượng
            $table->unsignedBigInteger('price')->default(0); // giá (VNĐ)
            $table->json('features')->nullable();        // các đặc điểm (JSON linh hoạt)
            $table->string('image')->nullable();
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
