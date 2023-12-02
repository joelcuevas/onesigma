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
        Schema::create('ladders', function (Blueprint $table) {
            $table->id();
            $table->string('ladderable_type');
            $table->bigInteger('ladderable_id');
            $table->string('ladder');
            $table->integer('technology');
            $table->integer('system');
            $table->integer('people');
            $table->integer('process');
            $table->integer('influence');
            $table->string('position')->nullable();
            $table->string('source')->default('auto');
            $table->foreignId('user_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ladders');
    }
};
