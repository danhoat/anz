<?php
declare(strict_types=1);

namespace QMS3\Brick\Step;


class Step
{
    const INPUT   = "INPUT";    // 入力画面
    const RESULT  = "RESULT";   // 確認画面での入力された値の表示
    const HIDDEN  = "HIDDEN";   // 確認画面の input[type=hidden]
    const CONFIRM = "CONFIRM";  // RESULT + HIDDEN
    const SUBMIT  = "SUBMIT";   // サンクスメール・通知メール 送信画面
    const THANKS  = "THANKS";   // 完了画面
    const MIXED   = "MIXED";    // ステップが一つに定まらない場合

    // ====================================================================== //

    /** @var    string */
    private $step;

    /**
     * @param    string|self    $step
     */
    public function __construct($step)
    {
        $this->step = $step instanceof self
            ? $step->value()
            : strtoupper(trim($step));
    }

    /**
     * @return    string
     */
    public function __toString()
    {
        return $this->step;
    }

    // ====================================================================== //

    /**
     * @return    string
     */
    public function value()
    {
        return $this->step;
    }

    /**
     * @param     string|self    $other
     * @return    bool
     */
    public function is($other)
    {
        $other_str = is_string($other) ? $other : $other->value();
        $other_str = strtoupper(trim($other_str));

        return $this->step == $other_str;
    }

    /**
     * @param     string[]|self[]    $others
     * @return    bool
     */
    public function in_array(array $others)
    {
        $other_strs = [];
        foreach ($others as $other) {
            $other_str = is_string($other) ? $other : $other->value();
            $other_str = strtoupper(trim($other_str));

            $other_strs[] = $other_str;
        }

        return in_array($this->step, $other_strs, /* $strict = */ true);
    }
}
