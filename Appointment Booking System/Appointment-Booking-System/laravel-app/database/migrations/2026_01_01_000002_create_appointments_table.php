<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('appointments', function (Blueprint $table) {
            $table->id();
            $table->string('customer_name', 100);
            $table->string('email', 120);
            $table->date('appointment_date');
            $table->time('appointment_time');
            $table->enum('status', ['Booked', 'Completed', 'Cancelled'])->default('Booked');
            $table->string('attachment')->nullable();
            $table->timestamps();

            $table->index(['appointment_date', 'appointment_time']);
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('appointments');
    }
};
