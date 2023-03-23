<?php
declare(strict_types=1);

namespace QMS3\Brick\ServerRequest;

use QMS3\Brick\Util\StripslashesDeep;


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
        $this->global_get   = StripslashesDeep::strip( $global_get );
        $this->global_post  = StripslashesDeep::strip( $global_post );
        $this->global_files = StripslashesDeep::strip( $global_files );
    }

    /**
     * @param    string    $name
     * @return    mixed
     */
    public function get( $name )
    {
        if ( array_key_exists( $name, $this->global_files ) ) {
            return $this->global_files[ $name ];
        }

        if ( array_key_exists( $name, $this->global_post ) ) {
            return $this->global_post[ $name ];
        }

        if ( array_key_exists( $name, $this->global_get ) ) {
            return $this->global_get[ $name ];
        }

        return null;
    }

    /**
     * @param     string    $name
     * @return    bool
     */
    public function has( $name )
    {
        return array_key_exists( $name, $this->global_files )
            || array_key_exists( $name, $this->global_post )
            || array_key_exists( $name, $this->global_get );
    }
}
