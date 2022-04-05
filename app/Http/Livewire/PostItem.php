<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Illuminate\Support\Str;

class PostItem extends Component
{
    public $post;

    public function render() {
        $this->post->slug = Str::slug($this->post->post_title);
        return view('livewire.post-item');
    }
}
