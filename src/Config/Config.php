<?php
namespace Gkr\Support\Config;

use Illuminate\Foundation\Application;

class Config{
    protected $app;
    protected $driver;
    public function __construct(Application $app)
    {
        $this->app = $app;
        $this->driver = $this->app['config'];
    }
    public function driver($type = null,$path = null,$force = true)
    {
        if (!$type || !$path){
            return $this->app['config'];
        }
        $className = "Gkr\\Support\\Config\\Drivers\\".ucwords($type);
        if (!class_exists($className)){
            throw new \Exception("{$type} config driver not exists!");
        }
        $this->driver = new $className($path,$force,$this->app);
        return $this;
    }

    public function __call($method, $arguments)
    {
        if (method_exists($this, $method)) {
            return call_user_func_array([$this, $method], $arguments);
        }
        return call_user_func_array([$this->driver, $method], $arguments);
    }
}