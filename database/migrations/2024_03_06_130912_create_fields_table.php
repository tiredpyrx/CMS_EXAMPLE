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
        Schema::create('fields', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id');
            $table->foreignId('blueprint_id')->nullable()->cascadeOnDelete();
            $table->foreignId('category_id')->nullable()->cascadeOnDelete();
            $table->foreignId('post_id')->nullable()->cascadeOnDelete()->cascadeOnUpdate();

            $table->string('label')->nullable();
            $table->string('column')->nullable()->default('6');
            $table->string('placeholder')->nullable();
            $table->text('description')->nullable();
            $table->string('handler');

            $table->string('value')->nullable();

            $table->string('type')->nullable()->default('text');

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
        Schema::dropIfExists('fields');
    }
};
