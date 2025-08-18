<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    if (!Schema::hasTable('pivot_category_post')) {
        Schema::create('pivot_category_post', function (Blueprint $table) {
            $table->id(); // 主キー（Aiven対策）
                $table->foreignId('category_id')->constrained('categories')->cascadeOnDelete();
                $table->foreignId('post_id')->constrained('posts')->cascadeOnDelete();
                $table->timestamps();

                // 同じ組み合わせの重複を禁止
                $table->unique(['category_id', 'post_id'], 'uq_pivot_category_post');
        });
    }
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pivot_category_post');
    }
};
