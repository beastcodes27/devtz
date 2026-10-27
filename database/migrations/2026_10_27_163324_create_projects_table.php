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
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('client')->nullable();
            $table->string('category');
            $table->text('summary');
            $table->longText('description')->nullable();
            $table->string('status')->default('draft');
            $table->unsignedTinyInteger('progress')->default(0);
            $table->date('started_on')->nullable();
            $table->date('due_on')->nullable();
            $table->string('project_url')->nullable();
            $table->json('tags')->nullable();
            $table->string('theme')->default('violet');
            $table->boolean('is_featured')->default(false);
            $table->unsignedSmallInteger('display_order')->default(0);
            $table->timestamps();

            $table->index(['status', 'created_at']);
            $table->index(['is_featured', 'display_order']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};
