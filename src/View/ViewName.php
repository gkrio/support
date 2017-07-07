<?php
namespace Gkr\Support\View;

use Illuminate\Support\Arr;
use Illuminate\View\ViewFinderInterface;

class ViewName
{
    /**
     * Normalize the given event name.
     *
     * @param  string  $name
     * @return string
     */
    public static function normalize($name)
    {
        $delimiter = ViewFinderInterface::HINT_PATH_DELIMITER;

        if (strpos($name, $delimiter) === false) {
            return str_replace('/', '.', $name);
        }
        $nameArray = explode($delimiter, $name);
        $name = str_replace('/', '.', $nameArray[count($nameArray) - 1]);
        $namespace = implode($delimiter,Arr::except($nameArray,count($nameArray) - 1));
        return $namespace.$delimiter.$name;
    }
}