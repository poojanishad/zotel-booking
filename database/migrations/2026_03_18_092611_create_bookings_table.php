<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('room_type_id'); 

            $table->date('check_in');  
            $table->date('check_out');

            $table->integer('rooms_booked'); 

            $table->timestamps();

            $table->foreign('room_type_id')
                  ->references('id')
                  ->on('room_types')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};