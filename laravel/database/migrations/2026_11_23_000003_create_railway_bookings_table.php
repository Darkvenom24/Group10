<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('railway_bookings', function (Blueprint $table) {
            $table->id();
            $table->string('pnr', 25)->unique();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('railway_system_id')->constrained('railway_systems')->onDelete('cascade');
            $table->string('passenger_name', 120);
            $table->integer('passenger_age')->default(25);
            $table->string('passenger_gender', 20)->default('Male');
            $table->string('passenger_phone', 30)->default('9876543210');
            $table->string('train_number', 20);
            $table->string('train_name', 150);
            $table->string('source_station', 100);
            $table->string('destination_station', 100);
            $table->string('travel_class', 10)->default('3A');
            $table->string('quota', 20)->default('GN');
            $table->integer('passengers_count')->default(1);
            $table->date('journey_date');
            $table->decimal('total_fare', 10, 2);
            $table->string('status', 30)->default('Confirmed');
            $table->string('coach_seat', 30)->nullable();
            $table->text('remarks')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'created_at']);
            $table->index(['railway_system_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('railway_bookings');
    }
};
