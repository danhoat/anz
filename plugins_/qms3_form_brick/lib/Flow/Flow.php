<?php
declare(strict_types=1);

namespace QMS3\Brick\Flow;

use QMS3\Brick\Step\Step;
use QMS3\Brick\Util\Template;


class Flow
{
    const TEMPLATES_DIR = __DIR__ . "/../../templates/flow";

    /** @var    Step */
    private $step;

    /** @var    string[] */
    private $steps;

    /** @var    Template */
    private $template;

    /**
     * @param    Step            $step
     * @param    int|string[]    $steps
     */
    public function __construct(Step $step, $steps)
    {
        assert(
            is_array($steps) || $steps == 2 || $steps == 3,
            "\$steps を整数値で指定する場合、値は 2 または 3 でなくてはいけません"
        );

        $this->step  = $step;
        $this->steps = $this->init_steps($steps);

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

    // ====================================================================== //

    /**
     * @param     int|string[]    $steps
     * @return    string[]
     */
    private function init_steps($steps)
    {
        if (is_array($steps)) {
            $sanitized = [];
            foreach ($steps as $key => $step) {
                // $steps の $key は文字列であって欲しい
                // $key の型を調べて、文字列でなければ $step を代わりに $key として使う
                $str_key = is_string($key) ? $key : $step;

                $sanitized[$str_key] = $step;
            }

            return $sanitized;
        } else if ($steps == 2) {
            return [
                "input"  => "入力画面",
                "thanks" => "完了画面",
            ];
        } else if ($steps == 3) {
            return [
                "input"   => "入力画面",
                "confirm" => "確認画面",
                "thanks"  => "完了画面",
            ];
        }
    }

    /**
     * @param     string    $step
     * @return    bool
     */
    public function is_current($step)
    {
        return $this->step->is($step);
    }

    // ====================================================================== //

    /**
     * @param     string|null    $step
     * @return    string
     */
    public function render($step = null)
    {
        if (is_string($step)) { $this->step = new Step($step); }

        $this->template->register("is_current", [ $this, "is_current" ]);

        return $this->template->render(
            "flow",
            [
                "steps"   => $this->steps,
            ]
        );
    }

    /**
     * @param     string|Step    $step
     * @return    self
     */
    public function current($step)
    {
        $this->step = new Step($step);

        return $this;
    }
}
