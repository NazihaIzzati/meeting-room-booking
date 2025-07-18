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
        Schema::table('bookings', function (Blueprint $table) {
            $table->string('recurrence')->nullable(); // none, daily, weekly, monthly
            $table->date('recurrence_end_date')->nullable();
            $table->unsignedBigInteger('parent_booking_id')->nullable();
            $table->foreign('parent_booking_id')->references('id')->on('bookings')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropForeign(['parent_booking_id']);
            $table->dropColumn(['recurrence', 'recurrence_end_date', 'parent_booking_id']);
        });
    }
};
