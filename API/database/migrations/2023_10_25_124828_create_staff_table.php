<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::create('staff', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained();
            $table->foreignId('location_id')->constrained();
            // $table->unsignedBigInteger('user_id');
            // $table->foreign('user_id')->references('id')->on('users');
            // $table->unsignedBigInteger('location_id');
            // $table->foreign('location_id')->references('id')->on('locations');
            $table->boolean('is_admin')->default(false);
            $table->string('phone');
            $table->timestamps();
        });

        // Create an indexes on the 'user_id', 'location_id' and 'is_admin' columns,
        // to speed up the search for staff by these columns.
        Schema::table('staff', function (Blueprint $table) {
            $table->index('user_id');
            $table->index('location_id');
            $table->index('is_admin');
        });

    }
    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::dropIfExists('staff');
    }
};