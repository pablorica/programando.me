<?php


use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * Returns the slug for this record
 * 
 * @param object $table
 * @param object $record
 * @return string|null
 */
if (! function_exists('create_slug')) {
    function create_slug($table, $record, $newslug) : string|null
    {
		$tableslug = $table->slug;

        if(!$newslug){
			//Log::debug("app/helpers.php -> Check if title or slug have changed for ".$record->id);
			$sameTitle = DB::table($table->name)
				->where($table->title,$record->title)
				->where('id', $record->id)
				->count();

			if(!$sameTitle) {
				//Log::debug("app/helpers.php -> making post slug based on title for post ".$record->id);
				// produce a slug based on the title
				$newslug = Str::slug($record->title);        
			}

			$sameSlug = DB::table($table->name)
				->where($tableslug,$record->slug)
				->where('id', $record->id)
				->count();

			if(!$sameSlug) {
				//Log::debug("app/helpers.php -> updating slug for post ".$record->id);
				$newslug = $record->slug;
			}
		}

        if($newslug) {
            //Log::debug("app/helpers.php -> temporary slug created: ". $newslug);
            
            // check to see if any other slugs exist that are the same & get the last of them
            $lastnum = false;

			if($record) {
				$last = DB::table($table->name)
					->select($tableslug)
					->whereRaw($tableslug." RLIKE '^{$newslug}(-[0-9]+)?$'")
					->whereNot('id', $record->id)
					->orderByraw('LENGTH(`'.$tableslug.'`) DESC')
					->orderBy($tableslug,'desc')
					->first();
			} else {
				$last = DB::table('posts')
					->select('post_slug')
					->whereRaw("post_slug RLIKE '^{$newslug}(-[0-9]+)?$'")
					->orderByraw('LENGTH(`post_slug`) DESC')
					->orderBy('post_slug','desc')
					->first();
			}

            
            if($last) {
                //Log::debug("app/helpers.php -> last slug created: ". print_r($last->$tableslug,true));
                
                $lastnum = 1;
                if(preg_match_all('/\d+/', $last->$tableslug, $numbers)) {
                    $lastnum += end($numbers[0]);
                }
                //Log::debug("app/helpers.php -> last number in slug: ". $lastnum);
            }

            // if other slugs exist that are the same, append the count to the slug
            return $lastnum ? "{$newslug}-{$lastnum}" : $newslug;
		}

		return $record->slug;

    }
}