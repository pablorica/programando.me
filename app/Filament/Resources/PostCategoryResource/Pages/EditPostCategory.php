<?php

namespace App\Filament\Resources\PostCategoryResource\Pages;

use App\Filament\Resources\PostCategoryResource;
use Filament\Resources\Pages\EditRecord;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;


class EditPostCategory extends EditRecord
{
    protected static string $resource = PostCategoryResource::class;

    protected function mutateFormDataBeforeSave(array $data): array
    {

        $cat_table = new \stdClass();
		$cat_table->name  = 'post_categories';
		$cat_table->title = 'category_title';
		$cat_table->slug  = 'category_slug';

        $cat_record = new \stdClass();
        $cat_record->id    = $this->record->id;;
		$cat_record->title = $data['category_title'];
		$cat_record->slug  = $data['category_slug'];

        $data['category_slug'] = create_slug($cat_table, $cat_record, false);


        //Log::debug("Resources/PostCategoryResource/Pages/EditPostCategory.php -> Editing category ".print_r($data,true));

        return $data;
    }
}
