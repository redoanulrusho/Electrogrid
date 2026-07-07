<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('admins', function (Blueprint $table) {
            $table->id(); // Primary Key
            $table->string('username')->unique();
            $table->string('email')->unique();
            $table->string('password');
            $table->string('designation');
            $table->string('assigned_zone');
            $table->string('role')->default('admin');
            $table->timestamps(); // Created_at and Updated_at
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('admins');
    }
};