<?php
declare(strict_types=1);

namespace QMS3\Brick\Option;

use QMS3\Brick\Field\PrefField;
use QMS3\Brick\Step\Step;
use QMS3\Brick\Util\ReadonlyProps;
use QMS3\Brick\Util\Template;


/**
 * @property-read    string         $index
 * @property-read    string         $label
 * @property-read    string         $value
 * @property-read    bool           $selected
 *
 * @method    string         index()
 * @method    string         label()
 * @method    string         value()
 * @method    bool           selected()
 */
class PrefOption
{
    use ReadonlyProps;

    const TEMPLATES_DIR = __DIR__ . "/../../templates/option";

    /** @var    Template */
    private $template;

    /** @var    Step */
    private $step;

    /** @var    PrefField */
    private $parent;

    /** @var    int */
    private $index;

    /** @var    string */
    private $label;

    /** @var    string */
    private $value;

    /** @var    bool */
    private $selected;

    /**
     * @param    Step         $step
     * @param    PrefField    $parent
     * @param    int          $index
     * @param    string       $label
     * @param    string       $value
     * @param    bool         $selected
     */
    public function __construct(
        Step      $step,
        PrefField $parent,
        $index,
        $label,
        $value,
        $selected = false
    )
    {
        $this->template  = new Template(self::TEMPLATES_DIR);

        $this->step     = $step;
        $this->parent   = $parent;
        $this->index    = $index;
        $this->label    = $label;
        $this->value    = $value;
        $this->selected = $selected;
    }

    // ====================================================================== //

    /**
     * @return    string
     */
    protected function _index()
    {
        return str_pad((string) $this->index, 2, "0", STR_PAD_LEFT);
    }

    /**
     * @return    string
     */
    protected function _label()
    {
        return $this->label;
    }

    /**
     * @return    string
     */
    protected function _value()
    {
        return $this->value;
    }

    /**
     * @return    bool
     */
    protected function _selected()
    {
        return $this->selected;
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
            case Step::SUBMIT:
            case "PLAIN":
                return $this->render_plain();

            default: throw new \Exception();
        }
    }

    /**
     * @return    string
     */
    public function render_input()
    {
        return $this->template->render(
            "pref.input",
            [
                "field"  => $this->parent,
                "option" => $this,
            ]
        );
    }
}
