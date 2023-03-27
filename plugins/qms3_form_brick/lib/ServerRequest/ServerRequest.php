<?php
declare(strict_types=1);

namespace QMS3\Brick\ServerRequest;


class ServerRequest
{
    /** @var    array<string,mixed> */
    private $global_get;

    /** @var    array<string,mixed> */
    private $global_post;

    /** @var    array<string,mixed> */
    private $global_files;

    /**
     * @param    array<string,mixed>    $global_get
     * @param    array<string,mixed>    $global_post
     * @param    array<string,array>    $global_files
     */
    public function __construct(
        array $global_get,
        array $global_post,
        array $global_files
    )
    {
        $this->global_get   = $global_get;
        $this->global_post  = $global_post;
        $this->global_files = $global_files;
    }

    /**
     * @param     string    $name
     * @return    mixed
     */
    public function get($name)
    {
        if (isset($this->global_files[$name])) {
            return $this->global_files[$name];
        }

        if (isset($this->global_post[$name])) {
            return $this->global_post[$name];
        }

        if (isset($this->global_get[$name])) {
            return $this->global_get[$name];
        }

        return null;
    }

    /**
     * @param     string    $name
     * @return    bool
     */
    public function has($name)
    {
        return isset($this->global_files[$name])
            || isset($this->global_post[$name])
            || isset($this->global_get[$name]);
    }
}
