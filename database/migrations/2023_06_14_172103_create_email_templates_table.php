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
        Schema::create('email_templates', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->integer('user_id')->nullable();
            $table->string('subject');
            $table->text('body');
            $table->text('variables');
            $table->enum('status', ['1', '0'])->default('1')->comment('1-Active 0-Inactive');
            $table->string('puchline')->nullable();
            $table->string('footer')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('email_templates');
    }
};
