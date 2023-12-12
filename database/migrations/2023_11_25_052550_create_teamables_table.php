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
        Schema::create('teamables', function (Blueprint $table) {
            $table->foreignId('team_id');
            $table->string('teamable_type');
            $table->bigInteger('teamable_id')->unsigned();
            $table->string('role')->nullable();
            $table->tinyInteger('is_locked')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('teamables');
    }
};
