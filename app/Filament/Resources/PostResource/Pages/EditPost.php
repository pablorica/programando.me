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
        $post_table = new \stdClass();
		$post_table->name  = 'posts';
		$post_table->title = 'post_title';
		$post_table->slug  = 'post_slug';

        $post_record = new \stdClass();
        $post_record->id    = $this->record->id;;
		$post_record->title = $data['post_title'];
		$post_record->slug  = $data['post_slug'];

        $data['post_slug'] = create_slug($post_table, $post_record, false);

        if(!$data['post_author']) {
            //Log::debug("app/Filament/Resources/PostResource/Pages/EditPost.php -> Adding author to post: ". auth()->id());
            $data['post_author'] = auth()->id();
            //$data['last_edited_by_id'] = auth()->id();
        }

        if(!$data['post_category']) {
            //Log::debug("app/Filament/Resources/PostResource/Pages/EditPost.php -> Uncategorizing post ");
            $data['post_category'] = null;
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
