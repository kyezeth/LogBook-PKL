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
        Schema::table('users', function (Blueprint $table) {
            // Student identifiers
            $table->string('nis')->nullable()->unique()->after('name'); // Nomor Induk Siswa
            $table->string('nisn')->nullable()->unique()->after('nis'); // Nomor Induk Siswa Nasional (used for login)
            
            // Role management
            $table->enum('role', ['member', 'admin'])->default('member')->after('nisn');
            
            // Personal information
            $table->string('phone', 20)->nullable()->after('role');
            $table->text('address')->nullable()->after('phone');
            $table->date('birth_date')->nullable()->after('address');
            $table->enum('gender', ['male', 'female'])->nullable()->after('birth_date');
            
            // Institution info
            $table->string('institution')->nullable()->after('gender'); // School/company name
            $table->string('department')->nullable()->after('institution'); // Department/class
            $table->date('pkl_start_date')->nullable()->after('department');
            $table->date('pkl_end_date')->nullable()->after('pkl_start_date');
            
            // Profile
            $table->string('profile_photo')->nullable()->after('pkl_end_date');
            
            // Security fields
            $table->boolean('is_active')->default(true)->after('profile_photo');
            $table->integer('failed_login_attempts')->default(0)->after('is_active');
            $table->timestamp('locked_until')->nullable()->after('failed_login_attempts');
            
            // Indexes for performance
            $table->index('role');
            $table->index('is_active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'nis', 'nisn', 'role', 'phone', 'address', 'birth_date', 'gender',
                'institution', 'department', 'pkl_start_date', 'pkl_end_date',
                'profile_photo', 'is_active', 'failed_login_attempts', 'locked_until'
            ]);
        });
    }
};
