<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::create('locations', function (Blueprint $table) {
            $table->id();
            $table->string('city');
            $table->string('address');
            $table->integer('opening_hour_from');
            $table->integer('opening_hour_to');
            $table->string('opening_days');
            $table->string('phone');
            $table->string('email');
            $table->integer('slot_duration');
            $table->integer('max_booking');
            $table->integer('workspaces');
            $table->string('imap_host');
            $table->string('imap_pw');
            $table->softDeletes();
            $table->timestamps();
        });

        // Create an indexes on the 'city' column,
        // to speed up the search for locations by these column.
        Schema::table('locations', function (Blueprint $table) {
            $table->index('city');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::dropIfExists('locations');
    }
};