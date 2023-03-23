<?php
declare(strict_types=1);

namespace QMS3\Brick\PreProcess;

use Detection\MobileDetect;
use QMS3\Brick\Param\Param;
use QMS3\Brick\Step\Step;
use QMS3\Brick\Structure\Structure;
use QMS3\Brick\Values\Values;


/**
 * @since    1.5.2
 */
interface PreProcessInterface
{
    /**
     * @param     Structure       $structure
     * @param     Values          $values
     * @param     string          $form_type
     * @param     Param           $param
     * @param     Step            $step
     * @param     MobileDetect    $detect
     * @return    bool
     */
    public function process(
        Structure $structure,
        Values $values,
        $form_type,
        Param $param,
        Step $step,
        MobileDetect $detect
    );
}
