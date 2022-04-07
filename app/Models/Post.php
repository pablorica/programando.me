<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class Post extends Model
{
    use HasFactory;

    const EXCERPT_LENGTH = 100;

    protected $fillable = [ 
        'post_title', 
        'post_slug', 
        'post_content',
        'post_excerpt',
        'post_author',
        'post_readmore',
        'post_category',
        'post_image',
        'post_published',
        'published_at'
    ];

    protected $casts = [
        'post_published' => 'boolean',
    ];

    /**
     *
     * @return string
     */
    public function createExcerpt(): string|null
    {
        return Str::limit(
            strip_tags($this->post_content), 
            Post::EXCERPT_LENGTH
        );
    }

    /**
     * Builds a new slug for this post
     * @return string
     */
    public function generateSlug() {
        //Log::debug("making post slug");

        // produce a slug based on the title
        $newslug = Str::slug($this->post_title);

        // check to see if any other slugs exist that are the same & count them
        $count = static::whereRaw("post_slug RLIKE '^{$newslug}(-[0-9]+)?$'")->count();

        // if other slugs exist that are the same, append the count to the slug
        $slug = $count ? "{$newslug}-{$count}" : $newslug;

        //Log::debug("slug created: ". $slug);

        return $slug;
    }

    /**
     * Returns the user for this post
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class,'post_author')->withDefault();
    }

    /**
     * Returns the fetured image URL
     * @return string|null
     */
    public function featured_image_url() {
        if($this->post_image) {
            return Storage::url($this->post_image);
        }
        return null;
        
    }
}
