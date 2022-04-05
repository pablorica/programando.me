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

    public function postColumns($posts, $numcolumns) {
        //Log::debug("posts: ". print_r($posts,true) );
        $columns = Array();
        for($int =0; $int<count($posts); $int+=$numcolumns){
            //Log::debug("count: ".count($posts) );
            //Log::debug("numcolumns: ".$numcolumns );
            //Log::debug("int: ".$int );
            //Log::debug("array_slice(posts, $int, $numcolumns) ");
            $columns[] = array_slice($posts, $int, $numcolumns);
        }
        return $columns;
      }


        public function render() {
            $posts = Post::where(
                "post_title",
                "like",
                "%" . $this->search . "%"
            )->paginate(3);
            //Log::debug("posts: ". print_r($posts,true) );
        
            return view(
                "livewire.show-posts", 
                compact(
                    'posts'
                )
            );
        }
}
