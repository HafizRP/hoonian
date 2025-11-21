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
        Schema::create('properties_type', function(Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });

        Schema::create('properties', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->decimal('price', 15, 2); // lebih aman untuk nominal besar
            $table->string('address');
            $table->string('city');
            $table->string('thumbnail');
            $table->text('description');
            $table->float('land_area')->nullable();
            $table->float('building_area')->nullable();
            $table->integer('bedrooms')->nullable();
            $table->integer('bathrooms')->nullable();
            $table->integer('floors')->nullable();
            $table->string('maps_url');
            $table->boolean('featured');
            $table->boolean('popular');
            $table->enum('status', [0, 1]);

            $table->foreignId('owner_id')
                ->constrained('users')
                ->onDelete('cascade');

            $table->foreignId('property_type')->constrained('properties_type')->onDelete('cascade');
            $table->timestamps();
        });

        Schema::create('properties_gallery', function (Blueprint $table) {
            $table->id();
            $table->string('url');
            $table->foreignId('property_id')->constrained('properties')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('properties');
    }
};
