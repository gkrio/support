<?php
namespace Gkr\Support\Named;
use Illuminate\Support\Arr;
use Illuminate\Support\NamespacedItemResolver as BaseResolver;

class NamespacedItemResolver extends BaseResolver
{
    protected function parseNamespacedSegments($key)
    {
        $segments = explode('::', $key);
        $spaceSegments = implode("::",Arr::except($segments,count($segments) - 1));
        $itemSegments = explode('.', $segments[count($segments) - 1]);
        $groupAndItem = array_slice(
            $this->parseBasicSegments($itemSegments), 1
        );

        return array_merge([$spaceSegments], $groupAndItem);
    }
}