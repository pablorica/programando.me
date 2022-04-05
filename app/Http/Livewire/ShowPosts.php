<?php

namespace App\Http\Livewire;

use App\Models\Post;
use Livewire\Component;
use Livewire\WithPagination;

use Illuminate\Support\Facades\Log;

class ShowPosts extends Component
{
    use WithPagination;

    public $search = "";

    protected $queryString = ["search"];
 

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render() {

        if($this->search) {
            $posts = Post::where(
                "post_title",
                "like",
                "%" . $this->search . "%"
            )->orderBy('id', 'DESC')->paginate(4);
        } else {
            $posts = Post::orderBy('id', 'DESC')->paginate(4);
        }
        
        //Log::debug("posts: ". print_r($posts,true) );
    
        return view(
            "livewire.show-posts", 
            compact(
                'posts'
            )
        );
    }
}
