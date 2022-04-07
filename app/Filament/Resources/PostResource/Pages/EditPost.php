<?php

namespace App\Filament\Resources\PostResource\Pages;


use App\Filament\Resources\PostResource;
use Filament\Resources\Pages\EditRecord;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class EditPost extends EditRecord
{
    protected static string $resource = PostResource::class;


    protected function mutateFormDataBeforeFill(array $data): array
    {
        $data['post_author'] = auth()->id();
    
        return $data;
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $recordID = $this->record->id;
        $newslug = false;

        //Log::debug("app/Filament/Resources/PostResource/Pages/EditPost.php -> Check if title or slug have changed for ".$recordID);
        $sameTitle = DB::table('posts')
            ->where('post_title',$data['post_title'])
            ->where('id', $recordID)
            ->count();

        if(!$sameTitle) {
            //Log::debug("app/Filament/Resources/PostResource/Pages/EditPost.php -> making post slug based on title for post ".$recordID);
            // produce a slug based on the title
            $newslug = Str::slug($data['post_title']);        
        }

        $sameSlug = DB::table('posts')
            ->where('post_slug',$data['post_slug'])
            ->where('id', $recordID)
            ->count();

        if(!$sameSlug) {
            //Log::debug("app/Filament/Resources/PostResource/Pages/EditPost.php -> updating slug for post ".$recordID);
            $newslug = $data['post_slug'];
        }

        if($newslug) {
            //Log::debug("app/Filament/Resources/PostResource/Pages/EditPost.php -> temporary slug created: ". $newslug);
            
            // check to see if any other slugs exist that are the same & get the last of them
            $lastnum = false;
            $last = DB::table('posts')
                ->select('post_slug')
                ->whereRaw("post_slug RLIKE '^{$newslug}(-[0-9]+)?$'")
                ->whereNot('id', $recordID)
                ->orderByraw('LENGTH(`post_slug`) DESC')
                ->orderBy('post_slug','desc')
                ->first();
            
            if($last) {
                //Log::debug("app/Filament/Resources/PostResource/Pages/EditPost.php -> last slug created: ". print_r($last->post_slug,true));
                
                $lastnum = 1;
                if(preg_match_all('/\d+/', $last->post_slug, $numbers)) {
                    $lastnum += end($numbers[0]);
                }
                //Log::debug("app/Filament/Resources/PostResource/Pages/EditPost.php -> last number in slug: ". $lastnum);
            }

            // if other slugs exist that are the same, append the count to the slug
            $slug = $lastnum ? "{$newslug}-{$lastnum}" : $newslug;

            //Log::debug("app/Filament/Resources/PostResource/Pages/EditPost.php -> slug created: ". $slug);

            $data['post_slug'] = $slug;
        }

        if(!$data['post_author']) {
            //Log::debug("app/Filament/Resources/PostResource/Pages/EditPost.php -> Adding author to post: ". auth()->id());
            $data['post_author'] = auth()->id();
            //$data['last_edited_by_id'] = auth()->id();
        }

        if(!$data['post_category']) {
            //Log::debug("app/Filament/Resources/PostResource/Pages/EditPost.php -> Uncategorizing post ");
            $data['post_category'] = 'uncategorized';
            //$data['last_edited_by_id'] = auth()->id();
        } 


        if($data['post_published']) {
            $data = array_merge($data, PostResource::publishResource());
        } else {
            $data = array_merge($data, PostResource::unpublishResource());
        }

        //Log::debug("app/Filament/Resources/PostResource/Pages/EditPost.php -> Editing post ".print_r($data,true));

        return $data;
    }

    protected function afterSave(): void
    {
        if(!$this->record->post_excerpt) {

            $excerpt = $this->record->createExcerpt();
            //Log::debug("app/Filament/Resources/PostResource/Pages/CreatePost.php -> Create excerpt: ". $excerpt);

            $update = DB::table('posts')
                ->where('id', $this->record->id)
                ->update(['post_excerpt' =>$excerpt]);

        }
    }
}
