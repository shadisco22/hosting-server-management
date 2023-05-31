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
        Schema::create('hosting_plans', function (Blueprint $table) {
            $table->id();
            $table->string('package_type');
            $table->boolean('available');
            $table->string('space');
            $table->string('bandwidth');
            $table->string('email_accounts');
            $table->string('mysql_accounts');
            $table->string('php_enabled');
            $table->string('ssl_certificate');
            $table->string('duration');
            $table->double('yearly_price');
            $table->double('yearly_price_outside_syria');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hosting_plans');
    }
};
