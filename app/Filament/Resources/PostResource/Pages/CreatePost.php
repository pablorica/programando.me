<?php

namespace App\Filament\Resources\PostResource\Pages;

use App\Filament\Resources\PostResource;
use Filament\Resources\Pages\CreateRecord;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CreatePost extends CreateRecord
{
    protected static string $resource = PostResource::class;


    protected function mutateFormDataBeforeCreate(array $data): array
    {
        
        $post_table = new \stdClass();
		$post_table->name  = 'posts';
		$post_table->title = 'post_title';
		$post_table->slug  = 'post_slug';

        $newslug = $data['post_slug'] ?: Str::slug($data['post_title']);   

        $data['post_slug'] = create_slug($post_table, null, $newslug);

        if(!$data['post_author']) {
            //Log::debug("app/Filament/Resources/PostResource/Pages/CreatePost.php -> Adding author to post: ". auth()->id());
            $data['post_author'] = auth()->id();
            //$data['last_edited_by_id'] = auth()->id();
            //$this->form->model($this->record)->saveRelationships();
           // $this->form->model($data)->saveRelationships(); 
        } 

        if(!$data['post_category']) {
            //Log::debug("app/Filament/Resources/PostResource/Pages/CreatePost.php -> Uncategorizing post ");
            $data['post_category'] = null;
            //$data['last_edited_by_id'] = auth()->id();
        } 
        
        if($data['post_published']) {
            $data = array_merge($data, PostResource::publishResource());
            //Log::debug("app/Filament/Resources/PostResource/Pages/CreatePost.php -> Publishing post ".print_r($data,true));
        } 

        //Log::debug("app/Filament/Resources/PostResource/Pages/CreatePost.php -> Creating new post: " . print_r($data,true));
        return $data;
    }

    protected function afterCreate(): void
    {
        // Runs after the form fields are saved to the database.

        if(!$this->record->post_author) {
            //The author is not being saved in the database, so we are running thsi update query-
            //I don't know why this is happening, but it's realyted with the line $this->form->model($this->record)->saveRelationships(); in /vendor/filament/filament/src/Resources/Pages/CreateRecord.php

            $update = DB::table('posts')
                ->where('id', $this->record->id)
                ->update(['post_author' => auth()->id()]);

        }

        if(!$this->record->post_excerpt) {

            $excerpt = $this->record->createExcerpt();
            //Log::debug("app/Filament/Resources/PostResource/Pages/CreatePost.php -> Create excerpt: ". $excerpt);

            $update = DB::table('posts')
                ->where('id', $this->record->id)
                ->update(['post_excerpt' =>$excerpt]);

        }
    }
}
