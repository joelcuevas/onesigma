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
        Schema::create('identities', function (Blueprint $table) {
            $table->id();
            $table->morphs('identifiable');
            $table->string('source');
            $table->string('source_id')->nullable();
            $table->string('source_email')->nullable();
            $table->json('context')->nullable();
            $table->timestamps();

            $table->unique(
                ['identifiable_type', 'identifiable_id', 'source'],
                'identifiable_source_unique',
            );
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('identities');
    }
};
