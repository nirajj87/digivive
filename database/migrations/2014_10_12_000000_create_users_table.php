<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('first_name');
            $table->string('last_name')->nullable();
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('profile_img')->nullable();
            $table->string('company_name')->nullable();
            $table->integer('role_id');
            $table->json('permission')->nullable();
            $table->integer('country_id')->nullable();
            $table->string('timezone')->nullable();
            $table->string('date_format')->nullable();
            $table->timestamp('password_last_updated')->nullable();
            $table->date('dob')->nullable();
            $table->string('phone_number',15)->nullable();
            $table->enum('status', ['0', '1','2'])->default('0')->comment('0=>Inactive, 1=>Active, 2=>Suspended');
            $table->integer('is_two_factor_enable')->default('0')->nullable();
            $table->bigInteger('is_parent')->nullable();
            $table->bigInteger('created_by')->nullable();
            $table->bigInteger('updated_by')->nullable();
            $table->rememberToken()->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
};
