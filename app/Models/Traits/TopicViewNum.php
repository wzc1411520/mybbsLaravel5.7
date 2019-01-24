<?php
/**
 * Created by PhpStorm.
 * User: wzc
 * Date: 2019/1/24
 * Time: 20:34
 */

namespace App\Models\Traits;


use Illuminate\Support\Facades\Redis;

trait TopicViewNum
{
    public function recordVisit()
    {
        Redis::incr($this->visitsCacheKey());

        return $this;
    }

    public function visits()
    {
        return Redis::get($this->visitsCacheKey()) ?: 0;
    }

    public function resetVisits()
    {
        Redis::del($this->visitsCacheKey());

        return $this;
    }

    public function visitsCacheKey()
    {
        return "threads.{$this->id}.visits";
    }
}