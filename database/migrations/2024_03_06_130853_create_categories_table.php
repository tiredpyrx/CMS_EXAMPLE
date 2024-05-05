<?php

use App\Models\Category;
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
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id');
            $table->foreignId('parent_id')->nullable();
            $table->foreign('parent_id')->references('id')->on('categories')->cascadeOnDelete();

            $table->string('title')->unique();
            $table->string('icon')->nullable();
            $table->string('view')->nullable();
            $table->text('description')->nullable();

            $table->bigInteger('order')->nullable();

            $table->boolean('have_details')->nullable()->default(Category::DEFAULT_HAVE_DETAILS_VALUE);
            $table->boolean('as_page')->nullable()->default(Category::DEFAULT_AS_PAGE_VALUE);
            $table->boolean('active')->nullable()->default(Category::DEFAULT_ACTIVE_VALUE);
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
