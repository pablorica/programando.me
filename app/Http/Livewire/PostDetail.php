<?php

namespace App\Http\Livewire;

use App\Models\Post;
use Livewire\Component;

use Illuminate\Support\Facades\Log;

class PostDetail extends Component
{
    public $post;

    public function mount($slug)
    {
        $this->post = Post::firstWhere('post_slug', $slug);
        //Log::debug("app/Http/Livewire/PostDetail.php -> Post category ".print_r($this->post->category->category_title,true));
        
    }

    public function render()
    {
        
        return view('livewire.post-detail');
    }
}
