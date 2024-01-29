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
        Schema::table('engineers', function (Blueprint $table) {
            $table->foreignId('position_id')->nullable()->after('email');
            $table->dropColumn(['track']);
        });

        Schema::table('teams', function (Blueprint $table) {
            $table->foreignId('position_id')->nullable()->after('status');
            $table->dropColumn(['track']);
        });

        Schema::table('skillsets', function (Blueprint $table) {
            $table->foreignId('position_id')->nullable()->after('skillable_id');
            $table->dropIndex(['track']);
            $table->dropIndex(['group']);
            $table->dropColumn(['track', 'group', 'level']);
        });

        Schema::table('positions', function (Blueprint $table) {
            $table->foreignId('parent_id')->nullable()->after('type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
