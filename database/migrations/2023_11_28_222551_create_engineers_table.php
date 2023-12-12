<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Enums\EngineerCareer;
use App\Models\Enums\EngineerDomain;

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
            $table->string('career')->nullable()->default(EngineerCareer::Engineer->value);
            $table->string('career_level')->nullable();
            $table->string('domain')->nullable()->default(EngineerDomain::Software->value);
            $table->string('domain_level')->nullable();
            $table->string('email')->nullable();
            $table->tinyInteger('is_internal')->default(1);
            $table->bigInteger('velocity_id')->unsigned()->nullable();
            $table->string('github_email')->nullable();
            $table->foreignId('user_id')->nullable();
            $table->tinyInteger('is_guest')->default(0);
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
