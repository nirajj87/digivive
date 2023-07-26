<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    /*{
        "status": "1",
        "isLiveContent": "0",
        "parent_id": "0",
        "genre_code": "GEN2577",
        "category_name": "Hashtag"
        }*/
    public function up(): void
    {
        Schema::create('generes', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->comment('slug of a genere');
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
        Schema::dropIfExists('generes');
    }
};
