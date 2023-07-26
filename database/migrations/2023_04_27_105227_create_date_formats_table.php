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
        Schema::create('date_formats', function (Blueprint $table) {
            $table->id();
            $table->string('title')->comment('php date/time format display');
            $table->string('format_key')->comment('php date/time format code');
            $table->enum('flag', ['1', '0'])->nullable()->default('0')->comment('1-Time 0-Date');
            $table->unsignedInteger('order')->default('1');
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
        Schema::dropIfExists('date_formats');
    }
};
