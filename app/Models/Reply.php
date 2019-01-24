<?php

namespace App\Models;

use App\Models\Traits\Favoritable;
use App\Models\Traits\RecordsActivity;

class Reply extends Model
{
    use Favoritable;

    protected $fillable = ['content'];
    protected $appends = ['favoritesCount','isFavorited'];

    public function topic()
    {
        return $this->belongsTo(Topic::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
