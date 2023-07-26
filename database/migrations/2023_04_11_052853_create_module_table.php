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
        Schema::create('module', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique()->index()->comment('slug of a module');
            $table->unsignedInteger('parent_id')->default(0)->comment('zero or parent id');
            $table->string('icon')->nullable()->comment('icon of module');
            $table->string('active_icon')->nullable()->comment('Active icon of module');
            $table->unsignedSmallInteger('order')->default(10)->comment('Module to be display in order');
            $table->json('role')->comment('allowed roles list');
            $table->enum('status', ['1', '0'])->default('1')->comment('1-Active 0-Inactive');
            $table->unsignedInteger('created_by')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('module');
    }
};
