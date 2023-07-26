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
        Schema::create('bank_details', function (Blueprint $table) {
            $table->id();
            $table->string('beneficiary_name');
            $table->string('bank_name');
            $table->integer('account_number');
            $table->string('ifsc_code');
            $table->string('swift_code')->nullable();
            $table->json('bank_address');
            $table->string('cancelled_cheque');
            $table->string('payment_gateway_id')->nullable();
            $table->string('gstin');
            $table->string('cin');
            $table->string('pan');
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
        Schema::dropIfExists('bank_details');
    }
};
