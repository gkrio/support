<?php
namespace Gkr\Support\View;
use Illuminate\View\Factory as BaseFactory;

class Factory extends BaseFactory
{
    public function addNamespace($namespace, $hints)
    {
        $this->finder->addNamespace($namespace, $hints);

        return $this;
    }
    protected function normalizeName($name)
    {
        return ViewName::normalize($name);
    }
}