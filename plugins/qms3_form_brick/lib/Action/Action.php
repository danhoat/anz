<?php
declare(strict_types=1);

namespace QMS3\Brick\Action;

use QMS3\Brick\Step\Step;


class Action
{
    /** @var    Step */
    private $step;

    /**
     * @param    Step    $step
     */
    public function __construct(Step $step)
    {
        $this->step = $step;
    }

    // ====================================================================== //

    /**
     * @return    string
     */
    public function __toString()
    {
        return $this->render();
    }

    /**
     * @param     string|null    $step
     * @return    string
     */
    public function __invoke($step = null)
    {
        return $this->render($step);
    }

    // ====================================================================== //

    /**
     * @param     string|null    $step
     * @return    string
     */
    public function render($step = null)
    {
        $step = is_string($step) ? strtoupper($step) : $this->step->value();

        switch ($step) {
            case Step::INPUT  : return "?param=confirm";
            case Step::CONFIRM: return "?param=submit";

            default: throw new \Exception();
        }
    }
}
