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
     
        Schema::create('aws_storage_settings', function (Blueprint $table) {
            $table->id();
            $table->string('storage_type');
            $table->string('key');
            $table->string('secret');
            $table->string('region');
            $table->string('default_acl');
            $table->string('bucket');
            $table->string('row_folder');
            $table->string('content_folder');
            $table->string('backup_folder');
            $table->integer('user_id');
            $table->string('cdn_url');
            $table->enum('is_selected', ['1', '0'])->default('1')->comment('1-Active 0-Inactive');
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
        Schema::dropIfExists('aws_storage_settings');
    }
};
