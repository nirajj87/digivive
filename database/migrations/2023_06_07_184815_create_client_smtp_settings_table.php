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
        Schema::create('client_smtp_settings', function (Blueprint $table) {
            $table->id();
            $table->string('host');
            $table->string('user_name');
            $table->string('password');
            $table->string('port');
            $table->string('encryption');
            $table->string('from_email');
            $table->integer('user_id')->nullable();
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
        Schema::dropIfExists('client_smtp_settings');
    }
};
