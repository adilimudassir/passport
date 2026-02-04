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
        Schema::create('passports', function (Blueprint $table) {
            $table->id();
            $table->string('lga');
            $table->string('lastname');
            $table->string('givennames');
            $table->string('gender');
            $table->string('date_of_birth');
            $table->string('expiry_date');
            $table->string('passport_number')->unique();
            $table->string('nationality');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('passports');
    }
};
