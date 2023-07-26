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
        Schema::create('search_settings', function (Blueprint $table) {
            $table->id();
            $table->string('search_type')->comment('slug');;
            $table->string('app_id')->nullable();
            $table->string('key');
            $table->string('collection_name');
            $table->string('host')->nullable();
            $table->string('port')->nullable();
            $table->string('protocol')->nullable();
            $table->integer('user_id');
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
        Schema::dropIfExists('search_settings');
    }
};
