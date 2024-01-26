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
        Schema::create('position_levels', function (Blueprint $table) {
            $table->id();
            $table->string('track');
            $table->tinyInteger('skill');
            $table->text('l0_description');
            $table->text('l1_description');
            $table->text('l2_description');
            $table->text('l3_description');
            $table->text('l4_description');
            $table->text('l5_description');
            $table->timestamps();

            $table->unique(['track', 'skill']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('position_levels');
    }
};
