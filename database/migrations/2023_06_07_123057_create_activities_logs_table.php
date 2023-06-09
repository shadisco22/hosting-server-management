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
        Schema::create('activities_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId("user_id");
            $table->enum('activity_type', [
                'create', 'update', 'delete', 'approve',
                'declined', 'open', 'close'
            ])->default('create');
            $table->enum('on_table', ['users', 'customers', 'orders', 'hosting_plans', 'support_tickets'])->default('users');
            $table->integer('record_id');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activities_logs');
    }
};
