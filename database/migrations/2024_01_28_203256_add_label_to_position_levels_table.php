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
        Schema::table('position_levels', function (Blueprint $table) {
            $table->string('skill_label')->default('--')->after('skill');
        });

        Schema::table('positions', function (Blueprint $table) {
            $table->dropColumn([
                's0_label', 's1_label', 's2_label', 's3_label', 's4_label',
                's5_label', 's6_label', 's7_label', 's8_label', 's9_label',
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
    }
};
