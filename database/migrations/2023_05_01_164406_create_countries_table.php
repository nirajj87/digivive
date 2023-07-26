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
        Schema::create('countries', function (Blueprint $table) {
            $table->id();
            $table->string('title')->comment('country name');
            $table->string('iso3')->comment('iso3');
            $table->string('iso2')->comment('iso2');
            $table->string('phone_code')->comment('phone_code');
            $table->string('capital')->comment('capital');
            $table->string('currency')->comment('currency');
            $table->string('currency_name')->comment('currency_name');
            $table->string('currency_symbol')->comment('currency_symbol');
            $table->json('timezones')->comment('timezones');
            $table->string('latitude')->comment('latitude');
            $table->string('longitude')->comment('longitude');
            $table->string('emoji')->comment('emoji');
            $table->string('image')->comment('image')->nullable();
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
        Schema::dropIfExists('countries');
    }
};
