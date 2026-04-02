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
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->dateTime('start_date');
            $table->dateTime('end_date');
            $table->enum('type', ['simple', 'sectioned']);
            $table->foreignId('organizer_id')->constrained('users')->cascadeOnDelete();
            $table->string('photo')->nullable();

            //used only for simple events, ignored for sectioned events
            $table->unsignedInteger('capacity')->nullable();
            $table->string('location')->nullable();
            $table->timestamps();

            //indexes for filtering and sorting -DB search faster
            $table->index('organizer_id');
            $table->index('start_date');
            $table->index('type');
        });

        // capacity = 0 or negative is rejected
        DB::statement("
            ALTER TABLE events
            ADD CONSTRAINT events_capacity_check
            CHECK (capacity IS NULL OR capacity >= 1)
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
