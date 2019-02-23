<?php

namespace App\Models;

use App\Http\Requests\Request;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
//    public $fillable = [];
    public static  function getSelectOptions()
    {
        $options = self::select('id','name as text')->get();
        $selectOption = [];
        foreach ($options as $option){
            $selectOption[$option->id] = $option->text;
        }
        return $selectOption;
    }

    public function topics()
    {
        return $this->hasMany(Topic::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
