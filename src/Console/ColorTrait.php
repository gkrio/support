<?php
namespace Gkr\Support\Console;

trait ColorTrait
{
    protected function noteColor($message)
    {
        return "<fg=black;bg=white>[note] {$message} </>";
    }

    protected function askColor($key,$description = null)
    {
        $description = ($description) ? "[<fg=cyan>{$description}</>]" : '';
        return $key.$description;
    }

    protected function confirmColor($key,$description = null)
    {
        $description = ($description) ? "<fg=cyan>({$description})?</>" : '?';
        return $key.$description;
    }
}