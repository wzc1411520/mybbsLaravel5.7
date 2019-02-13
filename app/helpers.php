<?php
function route_class()
{
    return str_replace('.', '-', Route::currentRouteName());
}
function category_nav_active($category_id)
{
    return active_class((if_route('categories.show') && if_route_param('category', $category_id)));
}
function make_excerpt($value, $length = 200)
{
    $excerpt = trim(preg_replace('/\r\n|\r|\n+/', ' ', strip_tags($value)));
    return str_limit($excerpt, $length);
}
function storage_url($avatar){
    if (preg_match('/^(http)|(HTTP)$/i',$avatar)){
        return $avatar;
    }elseif(preg_match('/^\/storage/',$avatar)){
        return config('app.url').$avatar;
    }
    else{
        return config('app.url').$avatar;
    }
}
?>