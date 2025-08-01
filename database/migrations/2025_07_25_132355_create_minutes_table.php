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
        Schema::create('minutes', function (Blueprint $table) {
            $table->id();
             $table->foreignId('booking_id')->constrained('bookings')->onDelete('cascade'); // Reference to the booking
            $table->text('content'); // Minutes content
            $table->foreignId('created_by')->constrained('employees')->onDelete('cascade'); // User who created the minutes
            $table->foreignId('assigned_to')->nullable()->constrained('employees')->onDelete('set null'); // User assigned to review or follow up on the minutes
            $table->string('status')->default('draft'); // Status of the minutes (draft, final, reviewed)
            $table->text('notes')->nullable(); // Additional notes or comments
            $table->timestamp('due_date')->nullable();
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
        Schema::table('minutes', function (Blueprint $table) {
            $table->dropForeign(['booking_id']);
            $table->dropForeign(['created_by']);
            $table->dropForeign(['assigned_to']);
        });
        Schema::dropIfExists('minutes');
    }
};
