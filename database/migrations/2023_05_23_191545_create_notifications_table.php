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
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id');
            $table->foreignId('user_id')->nullable();
            $table->foreignId('customer_hosting_plan_id')->nullable();
            $table->enum('notification_type',['package_expiration','ticket_message','package_approvement']);
            $table->enum('receiver',['Customer','User'])->nullable();
            $table->string('content');
            $table->timestamps('seen_by_customer')->nullable();
            $table->timestamps('seen_by_user')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
