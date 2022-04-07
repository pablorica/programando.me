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
        
        $newslug = $data['post_slug'] ?: Str::slug($data['post_title']);   

        if($newslug) {

            //Log::debug("app/Filament/Resources/PostResource/Pages/CreatePost.php -> temporary slug created: ". $newslug);
            
            // check to see if any other slugs exist that are the same & get the last of them
            $lastnum = false;
            $last = DB::table('posts')
                ->select('post_slug')
                ->whereRaw("post_slug RLIKE '^{$newslug}(-[0-9]+)?$'")
                ->orderByraw('LENGTH(`post_slug`) DESC')
                ->orderBy('post_slug','desc')
                ->first();
            
            if($last) {

                //Log::debug("app/Filament/Resources/PostResource/Pages/CreatePost.php -> last slug created: ". print_r($last->post_slug,true));
                
                $lastnum = 1;
                if(preg_match_all('/\d+/', $last->post_slug, $numbers)) {
                    $lastnum += end($numbers[0]);
                }
                //Log::debug("app/Filament/Resources/PostResource/Pages/CreatePost.php -> last number in slug: ". $lastnum);
            }

            // if other slugs exist that are the same, append the count to the slug
            $slug = $lastnum ? "{$newslug}-{$lastnum}" : $newslug;

            //Log::debug("app/Filament/Resources/PostResource/Pages/CreatePost.php -> slug created: ". $slug);

            $data['post_slug'] = $slug;

        }

        if(!$data['post_author']) {
            Log::debug("app/Filament/Resources/PostResource/Pages/CreatePost.php -> Adding author to post: ". auth()->id());
            $data['post_author'] = auth()->id();
            //$data['last_edited_by_id'] = auth()->id();
            //$this->form->model($this->record)->saveRelationships();
           // $this->form->model($data)->saveRelationships(); 
        } 
        if(!$data['post_category']) {
            //Log::debug("app/Filament/Resources/PostResource/Pages/CreatePost.php -> Uncategorizing post ");
            $data['post_category'] = 'uncategorized';
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
