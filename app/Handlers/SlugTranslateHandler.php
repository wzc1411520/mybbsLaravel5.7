<?php
/**
 * Created by PhpStorm.
 * User: wzc
 * Date: 2019/1/21
 * Time: 15:20
 */

namespace App\Handlers;
use Overtrue\Pinyin\Pinyin;


class SlugTranslateHandler
{
    public function translate($text)
    {
        return str_slug(app(Pinyin::class)->permalink($text));
//        return $this->pinyin($text);
    }
    public function pinyin($text)
    {
        return str_slug(app(Pinyin::class)->permalink($text));
    }
}