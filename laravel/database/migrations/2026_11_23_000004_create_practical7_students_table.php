<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('practical7_students', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('enrollment', 30);
            $table->string('full_name', 120);
            $table->string('email', 120);
            $table->string('phone', 30);
            $table->string('department', 120);
            $table->string('semester', 30);
            $table->string('gender', 20)->default('Not Specified');
            $table->date('dob')->nullable();
            $table->text('skills')->nullable();
            $table->string('project_title', 255);
            $table->text('project_desc')->nullable();
            $table->text('address')->nullable();
            $table->string('status', 30)->default('Submitted');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('practical7_students');
    }
};
