<?php
namespace Gkr\Support\Config\Drivers;

class Json extends Base
{
    protected function create()
    {
        $this->fs->dumpFile($this->path,json_encode([], JSON_PRETTY_PRINT));
    }
    protected function read()
    {
        return json_decode(file_get_contents($this->path),true);
    }
    protected function flush()
    {
        $this->fs->dumpFile($this->path,json_encode($this->items,JSON_PRETTY_PRINT));
    }
}