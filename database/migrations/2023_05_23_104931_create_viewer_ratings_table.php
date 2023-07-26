<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    /*
    {
        "content_advisory_name": "Suitable for all",
        "type": "content_advisory",
        "view_order": "30",
        "status": "1"
        }
    */
    public function up(): void
    {
        Schema::create('viewer_ratings', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->comment('slug of a content advisory');
            $table->integer('view_order');
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
        Schema::dropIfExists('viewer_ratings');
    }
};
