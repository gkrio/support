<?php

namespace Gkr\Support\Config\Drivers;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\Arr;
use Illuminate\Contracts\Config\Repository as ConfigContract;
use Symfony\Component\Filesystem\Filesystem;

abstract class Base implements ConfigContract
{
    protected $path;
    protected $app;
    protected $force;
    protected $fs;
    protected $items = [];
    protected $merged = false;
    protected $mergeKey = null;

    public function __construct($path,$force, Application $app)
    {
        $this->path = $path;
        $this->app = $app;
        $this->force = $force;
        $this->fs = new Filesystem();
        $this->items = $this->initialize();
    }
    abstract protected function create();
    abstract protected function read();
    protected function initialize()
    {
        if ($this->merged && $this->mergeKey){
            return $this->app['config']->get($this->mergeKey,[]);
        }
        if (!$this->fs->exists($this->path)){
            if (!$this->force){
                throw new \Exception("config file {$this->path} not exists!");
            }
            $file = new \SplFileInfo($this->path);
            if (!$this->fs->exists($finderPath = $file->getPath())){
                $this->fs->mkdir($finderPath,0777);
            }
            if(!$this->fs->exists($filePath = $this->path)){
                $this->fs->touch($filePath);
            }
            call_user_func([$this,'create']);
        }
        return call_user_func([$this,'read']);
    }
    public function merge($key)
    {
        $config = $this->app['config']->get($key,[]);
        $this->app['config']->set($key,array_merge($config,$this->read()));
        $this->merged = true;
        $this->mergeKey = $key;
        return $this;
    }

    public function has($key)
    {
        return Arr::has($this->items, $key);
    }
    public function get($key, $default = null)
    {
        return Arr::get($this->items, $key, $default);
    }
    public function set($key, $value = null,$flush = true)
    {
        $keys = is_array($key) ? $key : [$key => $value];
        foreach ($keys as $key => $value) {
            Arr::set($this->items, $key, $value);
        }
        if ($this->merged && $this->mergeKey){
            $this->app['config']->set("{$this->mergeKey}.{$key}",$value);
        }
        if ($flush){
            call_user_func([$this,'flush']);
        }
        return $this;
    }

    public function prepend($key, $value,$flush = true)
    {
        $array = $this->get($key);

        array_unshift($array, $value);
        $this->set($key, $array);
        if ($this->merged && $this->mergeKey){
            $this->app['config']->prepend("{$this->mergeKey}.{$key}",$value);
        }
        if ($flush){
            call_user_func([$this,'flush']);
        }
        return $this;
    }
    public function unset($key,$flush=true)
    {
        $array = $this->get($key);
        if (is_array($array)){
            Arr::forget($this->items,$key);
        }
        if ($this->merged && $this->mergeKey){
            $this->app['config']->push("{$this->mergeKey}",$this->items);
        }
        if ($flush){
            call_user_func([$this,'flush']);
        }
        return $this;
    }
    public function unsetByValue($key,$value,$flush=true)
    {
        $array = $this->get($key);
        $index = array_search($value,$array);
        if ($index){
            unset($array[$index]);
            $this->set($key, $array);
        }
        if ($this->merged && $this->mergeKey){
            $this->app['config']->prepend("{$this->mergeKey}.{$key}",$value);
        }
        if ($flush){
            call_user_func([$this,'flush']);
        }
        return $this;
    }
    public function push($key, $value,$flush = true)
    {
        $array = $this->get($key);

        $array[] = $value;

        $this->set($key, $array);
        if ($this->merged && $this->mergeKey){
            $this->app['config']->push("{$this->mergeKey}.{$key}",$value);
        }
        if ($flush){
            call_user_func([$this,'flush']);
        }
        return $this;
    }
    public function all()
    {
        return $this->items;
    }
}