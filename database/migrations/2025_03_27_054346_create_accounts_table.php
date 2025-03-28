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
        Schema::create('accounts', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('user_id')->constrained('users')->onDelete('cascade');
            $table->string('account_name')->unique();
            $table->bigInteger('account_number')->unique();
            $table->enum('account_type', ['Personal', 'Business']);
            $table->enum('currency', ['USD', 'EUR', 'GBP']);
            $table->decimal('balance', 15, 2)->default(0);
            $table->softDeletes(); // Allows soft deletion
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('accounts');
    }
};
