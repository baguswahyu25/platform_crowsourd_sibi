<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('datasets', function (Blueprint $table) {
            $table->string('auto_validation_status')->default('pending')->change();
        });
    }

    public function down(): void
    {
        Schema::table('datasets', function (Blueprint $table) {
            $table->string('auto_validation_status')->default('passed')->change();
        });
    }
};
