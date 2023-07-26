<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    /**refrence field
     *{
       * "name": "Web",
      *  "slug": "web",
      *  "order": 3,
      *  "before_order": 3,
      *  "status": 1,
      *  "catlogue": "6464cee3137d0dacb5d093f6",
      *  "created_at": 1684573769,
      *  "updated_at": 1684573769
       * } 
     * 
     * 
     */
    public function up(): void
    {
        Schema::create('platforms', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->comment('slug of a Plateform');
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
        Schema::dropIfExists('platforms');
    }
};
