<?php
namespace Gkr\Support\Translation;
use Illuminate\Support\Arr;
use Illuminate\Translation\FileLoader as BaseLoader;

class FileLoader extends BaseLoader
{
    protected function loadNamespaced($locale, $group, $namespace)
    {
        if ($hint = Arr::get($this->hints,str_replace("::",'.',$namespace),null)) {
            $lines = $this->loadPath($hint, $locale, $group);
            return $this->loadNamespaceOverrides($lines, $locale, $group, $namespace);
        }

        return [];
    }
    protected function loadSinglePath($path)
    {
        if ($this->files->exists($path)) {
            return $this->files->getRequire($path);
        }

        return [];
    }
    protected function loadPath($path, $locale, $group)
    {
        if (is_array($path)){
            foreach ($path as $singlePath){
                $full = "{$singlePath}/{$locale}/{$group}.php";
                if ($this->files->exists($full)){
                    return $this->loadSinglePath($full);
                }
            }
            return [];
        }
        $full = "{$path}/{$locale}/{$group}.php";
        return $this->loadSinglePath($full);
    }
    protected function loadNamespaceOverrides(array $lines, $locale, $group, $namespace)
    {
        $namespace = str_replace('::','/',$namespace);
        $file = "{$this->path}/vendor/{$namespace}/{$locale}/{$group}.php";

        if ($this->files->exists($file)) {
            return array_replace_recursive($lines, $this->files->getRequire($file));
        }

        return $lines;
    }
    public function addNamespace($namespace, $hint)
    {
        Arr::set($this->hints,str_replace("::",'.',$namespace),$hint);
    }
    public function namespaces()
    {
        return $this->hints;
    }
}