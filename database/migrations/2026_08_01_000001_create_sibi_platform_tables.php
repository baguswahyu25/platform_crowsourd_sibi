<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('role')->default('contributor')->after('email');
            $table->string('avatar')->nullable()->after('role');
            $table->string('phone')->nullable()->after('avatar');
            $table->string('institution')->nullable()->after('phone');
            $table->boolean('is_active')->default(true)->after('institution');
            $table->string('otp_code')->nullable()->after('is_active');
            $table->timestamp('otp_expires_at')->nullable()->after('otp_code');
        });

        Schema::create('dataset_needs', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('category');
            $table->text('description')->nullable();
            $table->integer('target_count')->default(100);
            $table->integer('current_count')->default(0);
            $table->string('priority')->default('medium');
            $table->string('status')->default('active');
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

        Schema::create('datasets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('dataset_need_id')->nullable()->constrained()->nullOnDelete();
            $table->string('contributor_code')->nullable()->comment('Label Kontributor e.g. Kontributor 1');

            $table->string('title');
            $table->string('category');
            $table->string('sign_label');
            $table->text('description')->nullable();

            $table->string('file_path');
            $table->string('file_type')->default('video');
            $table->bigInteger('file_size')->default(0);

            // Auto Validation metadata (OpenCV Python Analysis)
            $table->float('brightness_score')->nullable();
            $table->string('brightness_status')->nullable();

            $table->float('blur_score')->nullable();
            $table->string('blur_status')->nullable();

            $table->float('freeze_percentage')->nullable();
            $table->string('freeze_status')->nullable();

            $table->integer('video_width')->nullable();
            $table->integer('video_height')->nullable();
            $table->float('video_fps')->nullable();
            $table->string('resolution_status')->nullable();

            $table->string('auto_validation_status')->default('passed');
            $table->text('validation_message')->nullable();

            // Overall Status
            $table->string('status')->default('pending');

            $table->timestamps();
        });

        Schema::create('validations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('dataset_id')->constrained()->cascadeOnDelete();
            $table->foreignId('validator_id')->constrained('users')->cascadeOnDelete();
            $table->string('status');
            $table->text('feedback')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('validations');
        Schema::dropIfExists('datasets');
        Schema::dropIfExists('dataset_needs');

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['role', 'avatar', 'phone', 'institution', 'is_active', 'otp_code', 'otp_expires_at']);
        });
    }
};
