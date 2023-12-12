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
        Schema::create('metrics', function (Blueprint $table) {
            $table->id();
            $table->string('metricable_type');
            $table->bigInteger('metricable_id');
            $table->date('period');
            $table->string('metric');
            $table->double('value', 8, 2);
            $table->tinyInteger('latest')->default(0);
            $table->json('context')->nullable();
            $table->timestamps();

            $table->index(['period', 'metric']);
            $table->index('latest');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('metrics');
    }
};
