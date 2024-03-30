<?php

use App\Models\Field;
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
            $table->foreignId('category_id')->nullable()->cascadeOnDelete();
            $table->foreignId('post_id')->nullable()->cascadeOnDelete();
            $table->foreignId('field_id')->nullable()->cascadeOnDelete();

            $table->string('label')->nullable();
            $table->string('placeholder')->nullable();
            $table->text('description')->nullable();
            $table->string('handler')->nullable();
            $table->longText('value')->nullable();

            $table->string('type')->nullable()->default(Field::getDefaultTypeValue());
            $table->string('min_value')->nullable();
            $table->string('max_value')->nullable();
            $table->string('step')->nullable();
            $table->string('column')->nullable()->default(Field::getDefaultColumnValue());

            $table->string('prefix')->nullable();
            $table->string('suffix')->nullable();

            $table->boolean('as_option')->nullable()->default(false);
            $table->boolean('required')->nullable()->default(Field::getDefaultRequiredValue());
            $table->boolean('sluggable')->nullable()->default(Field::getDefaultsluggableValue());
            $table->boolean('active')->nullable()->default(Field::getDefaultActiveValue());

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
