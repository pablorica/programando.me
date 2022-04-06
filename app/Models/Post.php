<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    protected $fillable = [ 
        'post_title', 
        'post_slug', 
        'post_content',
        'post_excerpt',
        'post_author',
        'post_readmore',
        'post_category',
        'post_image',
        'post_author'
    ];

    /**
     * Returns the user for this post
     */
    public function user() {
        return $this->belongsTo(User::class)->withDefault();
    }
}
