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
        Schema::create('engineers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('ladder')->nullable();
            $table->integer('level')->nullable();
            $table->string('position')->nullable();
            $table->string('email')->nullable();
            $table->tinyInteger('internal')->default(1);
            $table->string('github_user')->nullable();
            $table->bigInteger('velocity_id')->unsigned()->nullable();
            $table->foreignId('user_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('engineers');
    }
};
