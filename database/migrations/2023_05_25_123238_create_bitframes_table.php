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
        Schema::create('bitframes', function (Blueprint $table) {
            $table->id();
            $table->string('type');
            $table->string('presets');
            $table->enum('is_downloadable', ['1', '0'])->default('0')->comment('1-Yes 0-No');
            $table->string('download_type')->comment('HD', 'SD','HQ');
            $table->enum('status', ['1', '0'])->default('1')->comment('1-Active 0-Inactive');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bitframes');
    }
};
