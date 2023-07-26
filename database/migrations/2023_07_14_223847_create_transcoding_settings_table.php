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
        Schema::create('transcoding_settings', function (Blueprint $table) {
            $table->id();
            $table->json('video_presets_id');
            $table->json('video_download_type_id')->nullable();
            $table->json('audio_presets_id')->nullable();
            $table->json('audio_download_type_id')->nullable();
            $table->integer('user_id')->nullable();
            $table->enum('status', ['1', '0'])->default('1')->comment('1-Active 0-Inactive');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transcoding_settings');
    }
};
