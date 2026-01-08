<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $table = 'products';

    protected $fillable = [
        'name',
        'slug',
        'tags',
        'category_id',
        'image',
        'thumbnail',
        'specification',
        'price',
        'quantity',
        'status',
        'short_description',
        'description',

        // SEO
        'keywords',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'meta_robots',
        'meta_author',
        'meta_publisher',
        'meta_copyright',
        'meta_image',
        'meta_url',
        'meta_canonical',
        'meta_og_title',
        'meta_og_type',
        'meta_og_locale',
        'meta_og_site_name',
        'meta_og_image',
        'meta_og_description',
        'meta_og_url',
        'meta_twitter_card',
        'meta_twitter_site',
        'meta_twitter_title',
        'meta_twitter_description',
        'meta_twitter_image',
        'meta_twitter_creator',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
