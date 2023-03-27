<?php
declare(strict_types=1);

namespace QMS3\Brick\Util;

use League\Plates;
use Cocur\Slugify\Slugify;
use QMS3\Brick\Util\HumanReadableFilesize;


/**
 * @see    https://github.com/thephpleague/plates/tree/b1684b6f127714497a0ef927ce42c0b44b45a8af    thephpleague/plates
 * @see    https://github.com/cocur/slugify/tree/16cdd7e792657d524cde931ea666436623b23301          cocur/slugify
 */
class Template
{
    /** @var    Plates\Engine */
    private $templates;

    /**
     * @param    string|null    $directory
     * @param    string         $fileExtension
     */
    public function __construct($directory = null, $fileExtension = "php")
    {
        $this->templates = new Plates\Engine($directory, $fileExtension);

        $this->templates->registerFunction(
            "br",
            function($str) { return nl2br((string) $str, /* $is_xhtml = */ true); }
        );

        $slugify = new Slugify();
        $this->templates->registerFunction(
            "slugify",
            [$slugify, "slugify"]
        );

        $human_readable_filesize = new HumanReadableFilesize();
        $this->templates->registerFunction(
            "human_readable_filesize",
            [$human_readable_filesize, "reword"]
        );
    }

    /**
     * @param     string     $name
     * @param     mixed[]    $data
     * @return    string
     */
    public function render($name, array $data = [])
    {
        return $this->templates->render($name, $data);
    }

    /**
     * @param     string      $name
     * @param     callable    $callback
     * @return    self
     */
    public function register($name, callable $callback)
    {
        $this->templates->registerFunction($name, $callback);

        return $this;
    }
}
