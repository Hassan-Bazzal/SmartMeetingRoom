<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('attendees', function (Blueprint $table) {
            $table->id();
             $table->foreignId('booking_id')->constrained('bookings')->onDelete('cascade'); // Reference to the booking
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // User who is an attendee
            $table->string('status')->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('attendees', function (Blueprint $table) {
            $table->dropForeign(['booking_id']);
            $table->dropForeign(['user_id']);
        });
        Schema::dropIfExists('attendees');
    }
};
