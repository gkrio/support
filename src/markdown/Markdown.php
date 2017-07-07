<?php
namespace Gkr\Support\Markdown;

use Parsedown;
use HTMLPurifier_Config;
use HTMLPurifier;

class Markdown implements MarkdownContract
{
    /**
     * @var Parsedown
     */
    private $parser;

    /**
     * @var \HTMLPurifier
     */
    private $purifier;

    public function __construct()
    {
        $this->parser = new Parsedown();

        $purifierConfig = HTMLPurifier_Config::create([
            'Cache.DefinitionImpl' => null, // Disable caching
        ]);
        $this->purifier = new HTMLPurifier($purifierConfig);
    }

    /**
     * @param string $text
     *
     * @return string
     */
    public function toHtml($text)
    {
        $html = $this->parser->text($text);
        $safeHtml = $this->purifier->purify($html);

        return $safeHtml;
    }
}