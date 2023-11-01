<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password')->nullable();
            $table->string('invite_token')->nullable();
            $table->enum('role', ['member', 'staff'])->default('member');
            $table->rememberToken();

            // we remove the timestamp here because STaff and Member have timestamps
            // $table->timestamps();
        });

        // Create an indexes on the 'role', 'email' and 'password' columns,
        // to speed up the search for users by these columns.
        Schema::table('users', function (Blueprint $table) {
            $table->index('role');
            $table->index('email');
            $table->index('password');
            $table->index('name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::dropIfExists('users');
    }
};