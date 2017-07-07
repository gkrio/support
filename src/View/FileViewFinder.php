<?php

namespace Gkr\Support\View;

use Illuminate\Support\Str;
use InvalidArgumentException;
use Illuminate\Support\Arr;
use Illuminate\View\FileViewFinder as BaseFinder;

class FileViewFinder extends BaseFinder
{
    /**
     * Get the path to a template with a named path.
     *
     * @param  string  $name
     * @return string
     */
    protected function findNamespacedView($name)
    {
        $segments = $this->parseNamespaceSegments($name);
        return $this->findInPaths($segments['view'], Arr::get($this->hints,$segments['key']));
    }

    protected function findInPaths($name, $paths)
    {
        foreach ((array) $paths as $path) {
            if (Arr::accessible($path)){
                throw new InvalidArgumentException("View [$name] not found.");
            }
            foreach ($this->getPossibleViewFiles($name) as $file) {
                if ($this->files->exists($viewPath = $path.'/'.$file)) {
                    return $viewPath;
                }
            }
        }
        throw new InvalidArgumentException("View [$name] not found.");
    }

    protected function parseNamespaceSegments($name)
    {
        $segments = explode(static::HINT_PATH_DELIMITER, $name);

        if (count($segments) < 2) {
            throw new InvalidArgumentException("View [$name] has an invalid name.");
        }

        $namespace = implode('::',Arr::except($segments,count($segments) - 1));
        $key = Str::lower(str_replace('::','.',$namespace));
        $view = $segments[count($segments) - 1];
        if (! Arr::get($this->hints,$key,null)) {
            throw new InvalidArgumentException("No hint path defined for [{$namespace}].");
        }
        return compact('key','view');
    }
    public function addNamespace($namespace,$hints)
    {
        $hints = (array) $hints;
        $namespace = Str::lower(str_replace(static::HINT_PATH_DELIMITER,'.',$namespace));
        if ($before = Arr::get($this->hints,$namespace,null)){
            $hints = array_merge($before,$hints);
        }
        Arr::set($this->hints,$namespace,$hints);
    }

    /**
     * Prepend a namespace hint to the finder.
     *
     * @param  string $namespace
     * @param  string|array $hints
     * @return void
     */
    public function prependNamespace($namespace, $hints)
    {
        $hints = (array) $hints;
        $namespace = Str::lower(str_replace(static::HINT_PATH_DELIMITER,'.',$namespace));
        if ($before = Arr::get($this->hints,$namespace,null)){
            $hints = array_merge($hints,$before);
        }
        Arr::set($this->hints,$namespace,$hints);
    }

    /**
     * Replace the namespace hints for the given namespace.
     *
     * @param  string $namespace
     * @param  string|array $hints
     * @return void
     */
    public function replaceNamespace($namespace, $hints)
    {
        $namespace = Str::lower(str_replace(static::HINT_PATH_DELIMITER,'.',$namespace));
        Arr::set($this->hints,$namespace,(array)$hints);
    }
}