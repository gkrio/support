<?php
use Illuminate\Support\Arr;
if (! function_exists('array_merge_dep')) {
    function array_merge_dep($array1, $array2)
    {
        foreach ($array1 as $key => $value) {
            if (key_exists($key, $array2)) {
                $array1[$key] = is_array($value) ?
                    array_merge_dep($value, $array2[$key]) : $array2[$key];
            }
        }
        return $array1;
    }
}
if (! function_exists('array_contains')){
    function array_contains($array1,$array2,$emptyAllow = false)
    {
        if (!$array1 || is_null($array1)){
            return false;
        }
        $array2 = Arr::accessible($array2) ? $array2 : [$array2];
        if (!$emptyAllow && empty($array2)){
            return false;
        }
        return (count($array2) <= count($array1)) && (count(array_intersect($array1,$array2)) > 0);
    }
}
if (! function_exists('markdown')){
    function markdown($content)
    {
        return app('gkr.markdown')->toHtml($content);
    }
}
