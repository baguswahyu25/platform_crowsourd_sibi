<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('dataset_needs', function (Blueprint $table) {
            if (!Schema::hasColumn('dataset_needs', 'subcategory')) {
                $table->string('subcategory')->nullable()->after('category');
            }
            if (!Schema::hasColumn('dataset_needs', 'category_id')) {
                $table->string('category_id')->nullable()->after('subcategory');
            }
        });

        Schema::table('datasets', function (Blueprint $table) {
            if (!Schema::hasColumn('datasets', 'subcategory')) {
                $table->string('subcategory')->nullable()->after('category');
            }
            if (!Schema::hasColumn('datasets', 'story_content')) {
                $table->longText('story_content')->nullable()->after('description');
            }
            if (!Schema::hasColumn('datasets', 'video_duration')) {
                $table->float('video_duration')->nullable()->after('file_size');
            }
            if (!Schema::hasColumn('datasets', 'rejection_reason')) {
                $table->text('rejection_reason')->nullable()->after('validation_message');
            }
        });

        Schema::table('validations', function (Blueprint $table) {
            if (!Schema::hasColumn('validations', 'rejection_reason')) {
                $table->text('rejection_reason')->nullable()->after('feedback');
            }
        });
    }

    public function down(): void
    {
        Schema::table('validations', function (Blueprint $table) {
            $table->dropColumn(['rejection_reason']);
        });

        Schema::table('datasets', function (Blueprint $table) {
            $table->dropColumn(['subcategory', 'story_content', 'video_duration', 'rejection_reason']);
        });

        Schema::table('dataset_needs', function (Blueprint $table) {
            $table->dropColumn(['subcategory', 'category_id']);
        });
    }
};
