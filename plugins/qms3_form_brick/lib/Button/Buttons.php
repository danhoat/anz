<?php
declare(strict_types=1);

namespace QMS3\Brick\Button;

use QMS3\Brick\Step\Step;
use QMS3\Brick\Util\ReadonlyProps;
use QMS3\Brick\Util\Template;


class Buttons
{
    use ReadonlyProps;

    const TEMPLATES_DIR = __DIR__ . "/../../templates/buttons";

    /** @var    Step */
    private $step;

    /** @var    Template */
    private $template;

    /**
     * @param    Step    $step
     */
    public function __construct(Step $step)
    {
        $this->step = $step;

        $this->template = new Template(self::TEMPLATES_DIR);
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
            case Step::INPUT  : return $this->render_input();
            case Step::CONFIRM: return $this->render_confirm();

            default: throw new \Exception();
        }
    }

    /**
     * @return    string
     */
    public function render_input()
    {
        return $this->template->render("input");
    }

    /**
     * @return    string
     */
    public function render_confirm()
    {
        return $this->template->render("confirm");
    }
}
