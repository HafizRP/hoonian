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

        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->string('name');
        });

        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('bio')->nullable();
            $table->string('email')->unique();
            $table->string('password');
            $table->string('profile_img')->nullable();
            $table->string('wa_url')->nullable();
            $table->string('ig_url')->nullable();
            $table->string('tele_url')->nullable();
            $table->string('x_url')->nullable();
            $table->timestamp('email_verified_at')->nullable();


            $table->foreignId('role')
                ->constrained('roles')
                ->onDelete('cascade');

            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
