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
     Schema::create('inventories', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('room_type_id'); // MUST match id type

            $table->date('date');
            $table->integer('available_rooms');
            $table->decimal('base_price', 10, 2);

            $table->timestamps();

            $table->foreign('room_type_id')
                ->references('id')
                ->on('room_types')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventories');
    }
};
