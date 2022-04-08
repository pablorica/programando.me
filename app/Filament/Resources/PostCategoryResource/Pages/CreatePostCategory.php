<?php

namespace App\Filament\Resources\PostCategoryResource\Pages;

use App\Filament\Resources\PostCategoryResource;
use Filament\Resources\Pages\CreateRecord;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CreatePostCategory extends CreateRecord
{
    protected static string $resource = PostCategoryResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        
        $cat_table = new \stdClass();
		$cat_table->name  = 'post_categories';
		$cat_table->title = 'category_title';
		$cat_table->slug  = 'category_slug';

        $newslug = $data['category_slug'] ?: Str::slug($data['category_title']);   

        $data['category_slug'] = create_slug($cat_table, null, $newslug);

        return $data;
    }
}
