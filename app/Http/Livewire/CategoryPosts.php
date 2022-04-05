<?php

namespace App\Http\Livewire;

use App\Models\Post;
use Livewire\Component;

class CategoryPosts extends Component
{
    public $category;

    public function mount($category) {
        $this->category = $category;
    }

    public function render() {
        $category = $this->category;
        $posts = Post::where('post_category', $this->category)
        ->where('post_published', true)->paginate(4);

        return view(
            "livewire.show-posts", 
            compact(
                'posts',
                'category'
            )
        );
    }
}