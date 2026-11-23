<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('username', 50)->nullable()->unique()->after('name');
            $table->string('enrollment', 30)->nullable()->unique()->after('username');
            $table->string('role', 50)->default('Student')->after('email');
            $table->boolean('is_admin')->default(false)->after('role');
            $table->string('assigned_system_code', 20)->nullable()->after('is_admin');
            $table->string('avatar')->default('images/temp.jpeg')->after('assigned_system_code');
            $table->dateTime('last_login')->nullable()->after('avatar');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'username',
                'enrollment',
                'role',
                'is_admin',
                'assigned_system_code',
                'avatar',
                'last_login',
            ]);
        });
    }
};
