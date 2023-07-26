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
        Schema::create('user_profile', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id');
            $table->string('user_name');
            $table->string('designation')->nullable();
            $table->string('company_name')->nullable();
            $table->string('company_logo')->nullable();
            $table->string('department')->nullable();
            $table->json('address')->nullable();
            $table->json('communication_channel')->nullable();
            $table->json('billing')->nullable();
            $table->json('managers')->nullable();
            //$table->string('landmark')->nullable();
            //$table->integer('country_id')->nullable();
            //$table->bigInteger('state_id')->nullable();
            //$table->bigInteger('city_id')->nullable();
           // $table->string('zip_code')->nullable();
            $table->enum('status', ['1', '0', '2'])->default('1')->comment('1-Active 0-Inactive 2-Blocked');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_profile');
    }
};
