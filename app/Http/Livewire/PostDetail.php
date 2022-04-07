<?php

namespace App\Http\Livewire;

use App\Models\Post;
use Livewire\Component;


class PostDetail extends Component
{
    public $post;

    public function mount($slug)
    {
        $this->post = Post::firstWhere('post_slug', $slug);
    }

    public function render()
    {
        return view('livewire.post-detail');
    }
}
