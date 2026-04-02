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
        Schema::table('events', function (Blueprint $table) {
            $table->string('tags')->nullable();
            $table->text('requirements')->nullable();
        
            $table->boolean('is_free')->default(true);
            $table->decimal('price', 8, 2)->nullable();

            $table->enum('status', ['draft', 'published', 'cancelled'])->default('draft'); 

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('events', function (Blueprint $table) {
        $table->dropColumn([
            'tags',
            'requirements',
            'is_free',
            'price',
            'status',
        ]);
    });
    }
};
