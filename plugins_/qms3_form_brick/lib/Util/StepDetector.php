<?php
declare(strict_types=1);

namespace QMS3\Brick\Util;

use QMS3\Brick\Param\Param;
use QMS3\Brick\Step\Step;


class StepDetector
{
    /** @var    Param */
    private $param;

    /**
     * @param    Param    $param
     */
    public function __construct( Param $param )
    {
        $this->param = $param;
    }

    /**
     * @return    Step
     */
    public function detect()
    {
        // TODO: $_GET["param"] の値がそのまま step になればいいんじゃない？

        if ( $this->param->step ) { return $this->param->step; }

        switch ( true ) {
            case isset( $_GET[ 'param' ] ) && $_GET[ 'param' ] === 'submit' :
            case preg_match( '%/submit\.(php|html)$%', $_SERVER[ 'SCRIPT_NAME' ] ):
                    return new Step( Step::SUBMIT );

            case isset( $_GET[ 'param' ] ) && $_GET[ 'param' ] === 'confirm':
            case preg_match( '%/confirm\.(php|html)$%', $_SERVER[ 'SCRIPT_NAME' ] ):
                return new Step( Step::CONFIRM );

            case preg_match( '%/thanks\d*\.(php|html)$%', $_SERVER[ 'SCRIPT_NAME' ] ):
                return new Step( Step::THANKS );

            default: return new Step( Step::INPUT );
        }
    }
}
