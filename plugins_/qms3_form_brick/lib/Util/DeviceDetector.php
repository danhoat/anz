<?php
declare(strict_types=1);

namespace QMS3\Brick\Util;

use QMS3\Brick\Enum\Device;


class DeviceDetector
{
    /**
     * @param    array<string,mixed>    $global_server
     */
    public function __construct( array $global_server )
    {
        $this->global_server = $global_server;
    }

    /**
     * @return    Device
     */
    public function detect()
    {
        $user_agent = isset( $this->global_server[ 'HTTP_USER_AGENT' ] )
            ? $this->global_server[ 'HTTP_USER_AGENT' ]
            : '';

        if (
            strpos(
                /* $haystack = */ $this->global_server[ 'REQUEST_URI'],
                /* $needle = */ '/sp/'
            ) === false
            && ! preg_match( '/iPhone|iPod|Android/ui' , $user_agent )
        ) {
            return new Device( Device::PC );
        } else {
            return new Device( Device::SP );
        }
    }
}
