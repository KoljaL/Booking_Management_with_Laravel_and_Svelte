<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('member_id')->constrained();
            $table->foreignId('location_id')->constrained();
            $table->foreignId('staff_id')->constrained();
            // $table->unsignedBigInteger('member_id');
            // $table->unsignedBigInteger('location_id');
            // $table->unsignedBigInteger('staff_id');
            // $table->foreign('member_id')->references('id')->on('members');
            // $table->foreign('location_id')->references('id')->on('locations');
            // $table->foreign('staff_id')->references('id')->on('staff');
            $table->date('date');
            $table->string('time');
            $table->integer('slots');
            $table->integer('state');
            $table->timestamp('started_at')->nullable();
            $table->timestamp('ended_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });


        // Create an indexes on the 'member_id', 'location_id' and 'staff_id' columns,
        // to speed up the search for bookings by these columns.
        Schema::table('bookings', function (Blueprint $table) {
            $table->index('member_id');
            $table->index('location_id');
            $table->index('staff_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::dropIfExists('bookings');
    }
};