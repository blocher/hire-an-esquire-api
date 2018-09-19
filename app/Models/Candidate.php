<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Candidate extends Model
{
    
	public $guarded = ['id', 'created_at', 'updated_at', 'api_token'];

	public static function boot()
    {
        parent::boot();

        self::saving(function($model){
            $original = $model->getOriginal();
		    if (isset($original['status']) && $original['status'] == 'pending' && $model->status!='pending' ) {
		        $model->reviewed = true;
		    }
        });

    }


}