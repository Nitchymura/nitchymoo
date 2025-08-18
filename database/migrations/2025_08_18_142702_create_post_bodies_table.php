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
        Schema::create('post_bodies', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('post_id');
            $table->longText('photo');
            $table->integer('priority');
            $table->timestamps();
            $table->softDeletes();
            
            // 🔗 外部キー制約（詳細付き）
            $table->foreign('post_id')
                ->references('id')->on('posts')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('post_bodies');
    }
};
