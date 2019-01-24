<?php

namespace App\Models;

use App\Models\Traits\RecordsActivity;
use Illuminate\Database\Eloquent\Model;

class Favorite extends Model
{
//    use RecordsActivity;
    protected $guarded = [];
    public function favorited()
    {
        return $this->morphTo();
    }

    public function topic()
    {
        return $this->belongsTo(Topic::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
