<?php
declare(strict_types=1);

namespace QMS3\Brick\SubTask;

use Detection\MobileDetect;
use QMS3\Brick\Param\Param;
use QMS3\Brick\Step\Step;
use QMS3\Brick\Structure\Structure;
use QMS3\Brick\Values\Values;


/**
 * callable を SubTaskInterface に適合させるだけの薄いラッパー
 *
 * @example
 *     $callable = function ($form_type, $param, $form, $detect) { var_dump($form_type); };
 *     $sub_task = new SimpleSubTask($callable);
 *
 * @since    1.5.0
 */
class SimpleSubTask implements SubTaskInterface
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
    )
    {
        return call_user_func(
            $this->callback,
            $structure,
            $values,
            $form_type,
            $param,
            $step,
            $detect
        );
    }
}
