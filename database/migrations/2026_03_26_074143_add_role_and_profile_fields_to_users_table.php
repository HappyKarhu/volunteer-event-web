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
        $table->enum('role', ['organizer', 'volunteer'])->default('volunteer');
        $table->string('contact_email')->nullable();
        // organizer-specific fields
        $table->string('company_name')->nullable();
        $table->string('phone')->nullable();
        $table->string('website')->nullable();
        $table->string('logo')->nullable();

        // volunteer-specific fields
        $table->text('bio')->nullable();
        

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
        $table->dropColumn([
            'role',
            'contact_email',
            'company_name',
            'phone',
            'website',
            'logo',
            'bio',
        ]);
        });
    }
};
