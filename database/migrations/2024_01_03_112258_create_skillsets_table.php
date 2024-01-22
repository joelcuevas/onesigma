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
        Schema::create('skillsets', function (Blueprint $table) {
            $table->id();
            $table->morphs('skillable');
            $table->string('track')->index();
            $table->string('group')->index();
            $table->tinyInteger('level');
            $table->tinyInteger('score')->nullable();
            $table->date('date')->index();
            $table->string('source')->nullable();
            $table->tinyInteger('s0')->default(0);
            $table->tinyInteger('s1')->default(0);
            $table->tinyInteger('s2')->default(0);
            $table->tinyInteger('s3')->default(0);
            $table->tinyInteger('s4')->default(0);
            $table->tinyInteger('s5')->default(0);
            $table->tinyInteger('s6')->default(0);
            $table->tinyInteger('s7')->default(0);
            $table->tinyInteger('s8')->default(0);
            $table->tinyInteger('s9')->default(0);
            $table->timestamps();

            $table->index(['date', 'source']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('skillsets');
    }
};
