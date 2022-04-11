<?php

namespace App\Http\Livewire;

use App\Models\Post;
use Livewire\Component;

use Illuminate\Support\Facades\Log;

class CategoryPosts extends Component
{
    public $category;

    public function mount($category) {
        $this->category = $category;
    }

    public function render() {

        //Log::debug("app/Http/Livewire/CategoryPosts.php -> Category Slug quibusdam");
        $posts = Post::whereHas('category', function ($q) {
            $q->where('category_slug', $this->category);
        })->where('post_published', true)->paginate(4);
        //Log::debug("app/Http/Livewire/CategoryPosts.php -> Posts: ".print_r($posts,true));
        /*
        $tposts = Post::where('post_category', $this->category)
        ->where('post_published', true)->paginate(4);
        */

        //$category = $this->category;
        $cattitle = $posts->first()->category->category_title;
        //Log::debug("app/Http/Livewire/CategoryPosts.php -> Category: ".print_r($cattitle,true));
        
        return view(
            "livewire.show-posts", 
            compact(
                'posts',
                'cattitle'
            )
        );
    }
}