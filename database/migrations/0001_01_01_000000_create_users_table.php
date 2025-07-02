<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
public function up(): void
{
    Schema::create('users', function (Blueprint $table) {
        $table->id();
        $table->string('name');
        $table->string('email')->unique();
        $table->timestamp('email_verified_at')->nullable();
        $table->string('password');

        $table->enum('role', ['Admin', 'Faculty_Admin', 'Lecturer', 'Student'])->default('Student');
        $table->string('class_code')->nullable();     // for students
        $table->string('account_id')->nullable();     // for lecturers

        $table->rememberToken();
        $table->timestamps();
    });
}

};
