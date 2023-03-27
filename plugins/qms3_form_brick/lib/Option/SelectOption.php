<?php
declare(strict_types=1);

namespace QMS3\Brick\Option;

use QMS3\Brick\Field\SelectField;
use QMS3\Brick\Step\Step;
use QMS3\Brick\Util\ReadonlyProps;
use QMS3\Brick\Util\Template;


/**
 * @property-read    int            $index
 * @property-read    string         $label
 * @property-read    string         $value
 * @property-read    bool           $selected
 *
 * @method    int            index()
 * @method    string         label()
 * @method    string         value()
 * @method    bool           selected()
 */
class SelectOption
{
    use ReadonlyProps;

    const TEMPLATES_DIR = __DIR__ . "/../../templates/option";

    /** @var    Template */
    private $template;

    /** @var    Step */
    private $step;

    /** @var    SelectField */
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
     * @param    Step           $step
     * @param    SelectField    $parent
     * @param    int            $index
     * @param    string         $label
     * @param    string         $value
     * @param    bool           $selected
     */
    public function __construct(
        Step        $step,
        SelectField $parent,
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
     * @return    int
     */
    protected function _index()
    {
        return $this->index;
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
            "select.input",
            [
                "field"  => $this->parent,
                "option" => $this,
            ]
        );
    }
}
