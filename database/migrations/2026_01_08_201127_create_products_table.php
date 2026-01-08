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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('tags')->nullable();
            $table->bigInteger('category_id');
            $table->string('image')->nullable();
            $table->string('thumbnail')->nullable();
            $table->text('specification')->nullable();
            $table->integer('price')->default(0);
            $table->integer('quantity')->default(0);
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->text('short_description')->nullable();
            $table->longText('description')->nullable();

            // SEO
            $table->text('keywords')->nullable();
            $table->text('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->text('meta_keywords')->nullable();
            $table->text('meta_robots')->nullable();
            $table->text('meta_author')->nullable();
            $table->text('meta_publisher')->nullable();
            $table->text('meta_copyright')->nullable();
            $table->text('meta_image')->nullable();
            $table->text('meta_url')->nullable();
            $table->text('meta_canonical')->nullable();
            $table->text('meta_og_title')->nullable();
            $table->text('meta_og_type')->nullable();
            $table->text('meta_og_locale')->nullable();
            $table->text('meta_og_site_name')->nullable();
            $table->text('meta_og_image')->nullable();
            $table->text('meta_og_description')->nullable();
            $table->text('meta_og_url')->nullable();
            $table->text('meta_twitter_card')->nullable();
            $table->text('meta_twitter_site')->nullable();
            $table->text('meta_twitter_title')->nullable();
            $table->text('meta_twitter_description')->nullable();
            $table->text('meta_twitter_image')->nullable();
            $table->text('meta_twitter_creator')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
