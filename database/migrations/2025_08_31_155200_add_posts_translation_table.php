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
        Schema::table('posts', function (Blueprint $table) {
            // imageの後にcity、その後にcountryを追加
            $table->string('subtitle')->nullable()->after('title');
            $table->text('translation')->nullable()->after('description');
        });
    }

    public function down(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->dropColumn(['subtitle', 'translation']);
        });
    }
};
