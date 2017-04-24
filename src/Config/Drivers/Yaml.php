<?php
namespace Gkr\Support\Config\Drivers;
use Symfony\Component\Yaml\Yaml as sfYaml;
class Yaml extends Base
{
    public function create()
    {
        $this->fs->dumpFile($this->path,sfYaml::dump([]));
    }
    protected function read()
    {
        return sfYaml::parse(file_get_contents($this->path));
    }
    protected function flush()
    {
        $this->fs->dumpFile($this->path,sfYaml::dump($this->items,5,4));
    }
}