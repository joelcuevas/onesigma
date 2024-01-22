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
        Schema::create('positions', function (Blueprint $table) {
            $table->id();
            $table->string('type')->index();
            $table->string('track')->unique();
            $table->string('group')->index();
            $table->tinyInteger('level');
            $table->string('title');
            $table->string('s0_label');
            $table->tinyInteger('s0')->default(0);
            $table->string('s1_label');
            $table->tinyInteger('s1')->default(0);
            $table->string('s2_label');
            $table->tinyInteger('s2')->default(0);
            $table->string('s3_label');
            $table->tinyInteger('s3')->default(0);
            $table->string('s4_label');
            $table->tinyInteger('s4')->default(0);
            $table->string('s5_label');
            $table->tinyInteger('s5')->default(0);
            $table->string('s6_label');
            $table->tinyInteger('s6')->default(0);
            $table->string('s7_label');
            $table->tinyInteger('s7')->default(0);
            $table->string('s8_label');
            $table->tinyInteger('s8')->default(0);
            $table->string('s9_label');
            $table->tinyInteger('s9')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('positions');
    }
};
