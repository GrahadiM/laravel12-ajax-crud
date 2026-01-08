<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach (range(1, 250) as $index) {
            Product::create([
                'name' => 'Product ' . $index,
                'slug' => 'product-' . $index,
                'tags' => 'Tags ' . $index,
                'category_id' => mt_rand(1, 250),
                'image' => 'product-' . $index . '.webp',
                'thumbnail' => 'product-' . $index . '-thumb.webp',
                'specification' => 'Specification ' . $index,
                'price' => mt_rand(1000, 10000) * 100,
                'quantity' => mt_rand(1, 100),
                'status' => $status = ['active', 'inactive'][array_rand(['active', 'inactive'])],
                'short_description' => 'Short Description ' . $index,
                'description' => 'Description ' . $index,

                // SEO
                'keywords' => 'Keywords ' . $index,
                'meta_title' => 'Meta Title ' . $index,
                'meta_description' => 'Meta Description ' . $index,
                'meta_keywords' => 'Meta Keywords ' . $index,
                'meta_robots' => 'Meta Robots ' . $index,
                'meta_author' => 'Meta Author ' . $index,
                'meta_publisher' => 'Meta Publisher ' . $index,
                'meta_copyright' => 'Meta Copyright ' . $index,
                'meta_image' => 'Meta Image ' . $index,
                'meta_url' => 'Meta URL ' . $index,
                'meta_canonical' => 'Meta Canonical ' . $index,
                'meta_og_title' => 'Meta OG Title ' . $index,
                'meta_og_type' => 'Meta OG Type ' . $index,
                'meta_og_locale' => 'Meta OG Locale ' . $index,
                'meta_og_site_name' => 'Meta OG Site Name ' . $index,
                'meta_og_image' => 'Meta OG Image ' . $index,
                'meta_og_description' => 'Meta OG Description ' . $index,
                'meta_og_url' => 'Meta OG URL ' . $index,
                'meta_twitter_card' => 'Meta Twitter Card ' . $index,
                'meta_twitter_site' => 'Meta Twitter Site ' . $index,
                'meta_twitter_title' => 'Meta Twitter Title ' . $index,
                'meta_twitter_description' => 'Meta Twitter Description ' . $index,
                'meta_twitter_image' => 'Meta Twitter Image ' . $index,
                'meta_twitter_creator' => 'Meta Twitter Creator ' . $index,
            ]);
        }
    }
}
