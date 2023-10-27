<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::create('members', function (Blueprint $table) {
            // This is NOT the same ID as the "User" table's ID
            $table->id();

            // This is the ID of the user who is a member
            $table->foreignId('user_id')->constrained('users');

            // This is the ID of the staff member who created the member
            $table->unsignedBigInteger('staff_id');
            $table->foreign('staff_id')->references('id')->on('staff');


            // This is the ID of the location where the member is registered
            $table->unsignedBigInteger('location_id');
            $table->foreign('location_id')->references('id')->on('locations');

            // These are the attributes of the member
            $table->string('phone')->nullable();
            $table->string('jc_number')->nullable();
            $table->integer('max_booking')->default(1);
            $table->boolean('active')->default(true);
            $table->boolean('archived')->default(false);

            // we neen the timestamps here, 
            // because the User model does not have timestamps anymore
            $table->timestamps();
        });
    }
    // $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::dropIfExists('members');
    }
};