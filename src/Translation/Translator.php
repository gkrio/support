<?php
namespace Gkr\Support\Translation;
use Gkr\Support\Named\NamespacedItemResolver;
use Illuminate\Support\Arr;
use Illuminate\Translation\LoaderInterface;
use Illuminate\Translation\Translator as Base;

class Translator extends Base
{
    protected $namespaced;
    public function __construct(LoaderInterface $loader, $locale)
    {
        parent::__construct($loader,$locale);
        $this->namespaced = new NamespacedItemResolver();
    }
    protected function segments($namespace)
    {
        return str_replace("::",".",$namespace);
    }
    protected function getLine($namespace, $group, $locale, $item, array $replace)
    {
        $this->load($namespace, $group, $locale);

        $line = Arr::get($this->loaded,"{$this->segments($namespace)}.$group.$locale.$item");

        if (is_string($line)) {
            return $this->makeReplacements($line, $replace);
        } elseif (is_array($line) && count($line) > 0) {
            return $line;
        }
    }
    public function addLines(array $lines, $locale, $namespace = '*')
    {
        foreach ($lines as $key => $value) {
            list($group, $item) = explode('.', $key, 2);

            Arr::set($this->loaded, "{$this->segments($namespace)}.$group.$locale.$item", $value);
        }
    }
    public function load($namespace, $group, $locale)
    {
        if ($this->isLoaded($namespace, $group, $locale)) {
            return;
        }
        $lines = $this->loader->load($locale, $group, $namespace);
        Arr::set($this->loaded,"{$this->segments($namespace)}.{$group}.{$locale}",$lines);
    }
    protected function isLoaded($namespace, $group, $locale)
    {
        return Arr::has($this->loaded,"{$this->segments($namespace)}.{$group}.{$locale}");
    }
    public function parseKey($key)
    {
        $segments = $this->namespaced->parseKey($key);

        if (is_null($segments[0])) {
            $segments[0] = '*';
        }

        return $segments;
    }
}