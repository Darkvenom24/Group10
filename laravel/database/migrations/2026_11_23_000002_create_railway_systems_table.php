<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('railway_systems', function (Blueprint $table) {
            $table->id();
            $table->string('code', 20)->unique();
            $table->string('name', 150);
            $table->string('zone', 100);
            $table->string('division', 100);
            $table->string('headquarters', 120);
            $table->string('station_master_name', 120);
            $table->string('station_master_username', 60);
            $table->string('station_master_email', 120);
            $table->string('station_master_phone', 40)->default('+91 79 2214 5500');
            $table->integer('total_trains')->default(40);
            $table->integer('daily_passengers')->default(150000);
            $table->text('description')->nullable();
            $table->string('badge_color', 30)->default('#000080');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('railway_systems');
    }
};
