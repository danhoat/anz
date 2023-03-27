<?php
declare(strict_types=1);

namespace QMS3\Brick\Config;

use Detection\MobileDetect;
use QMS3\Brick\Config\ConfigInterface;
use QMS3\Brick\Param\Param;
use QMS3\Brick\Step\Step;
use QMS3\Brick\Structure\Structure;
use QMS3\Brick\Values\Values;


class ConstValue implements ConfigInterface
{
    /** @var    mixed */
    private $value;

    /**
     * @param    mixed    $value
     */
    public function __construct($value)
    {
        $this->value = $value;
    }

    // ====================================================================== //

    /**
     * @param     Structure       $structure
     * @param     Values          $values
     * @param     string          $form_type
     * @param     Param           $param
     * @param     Step            $step
     * @param     MobileDetect    $detect
     * @return    mixed
     */
    public function get_value(
        Structure $structure,
        Values $values,
        $form_type,
        Param $param,
        Step $step,
        MobileDetect $detect
    )
    {
        return $this->value;
    }
}
