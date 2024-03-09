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
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id');
            $table->foreignId('parent_id')->nullable();
            $table->foreign('parent_id')->references('id')->on('categories')->cascadeOnDelete();

            $table->string('title')->unique();
            $table->string('icon')->nullable();
            $table->string('view')->nullable();
            $table->text('description')->nullable();

            $table->boolean('have_details')->nullable()->default(false);
            $table->boolean('direct_access')->nullable()->default(false);
            $table->boolean('active')->nullable()->default(true);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};
