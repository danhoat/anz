<?php
declare(strict_types=1);

namespace QMS3\Brick\PreProcess;

use Detection\MobileDetect;
use QMS3\Brick\Param\Param;
use QMS3\Brick\PreProcess\PreProcessInterface;
use QMS3\Brick\Step\Step;
use QMS3\Brick\Structure\Structure;
use QMS3\Brick\Values\Values;


/**
 * callable を PreProcessInterface に適合させるだけの薄いラッパー
 *
 * @example
 *     $callable = function ($form_type, $param, $form, $detect) { var_dump($form_type); };
 *     $post_process = new SimplePostProcess($callable);
 *
 * @since    1.5.2
 */
class SimplePreProcess implements PreProcessInterface
{
    /** @var    callable */
    private $callback;

    /**
     * @param    callable    $callback
     */
    public function __construct(callable $callback)
    {
        $this->callback = $callback;
    }

    /**
     * @param     string          $form_type
     * @param     Param           $param
     * @param     Structure       $structure
     * @param     Step            $step
     * @param     Values          $values
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
    )
    {
        return call_user_func(
            $this->callback,
            $structure,
            $values,
            $form_type,
            $param,
            $detect
        );
    }
}
