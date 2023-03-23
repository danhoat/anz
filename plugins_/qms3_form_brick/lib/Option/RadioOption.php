<?php
declare(strict_types=1);

namespace QMS3\Brick\Option;

use QMS3\Brick\Enum\ExtraInputType;
use QMS3\Brick\Field\RadioField;
use QMS3\Brick\Step\Step;
use QMS3\Brick\Util\ReadonlyProps;
use QMS3\Brick\Util\Template;


/**
 * @property-read    int                    $index
 * @property-read    string                 $label
 * @property-read    string                 $value
 * @property-read    bool                   $checked
 * @property-read    string|null            $figure
 * @property-read    ExtraInputType|null    $extra_input_type
 * @property-read    string|null            $extra_input_name
 * @property-read    string|null            $extra_input_placeholder
 *
 * @method    int                    index()
 * @method    string                 label()
 * @method    string                 value()
 * @method    bool                   checked()
 * @method    string|null            figure()
 * @method    ExtraInputType|null    extra_input_type()
 * @method    string|null            extra_input_name()
 * @method    string|null            extra_input_placeholder()
 */
class RadioOption
{
    use ReadonlyProps;

    const TEMPLATES_DIR = __DIR__ . "/../../templates/option";

    /** @var    Template */
    private $template;

    /** @var    Step */
    private $step;

    /** @var    RadioField */
    private $parent;

    /** @var    int */
    private $index;

    /** @var    string */
    private $label;

    /** @var    string */
    private $value;

    /** @var    bool */
    private $checked;

    /** @var    string|null */
    private $figure;

    /** @var    ExtraInputType|null */
    private $extra_input_type;

    /** @var    string|null */
    private $extra_input_name;

    /** @var    string|null */
    private $extra_input_value;

    /** @var    string|null */
    private $extra_input_placeholder;

    /**
     * @param    Step                   $step
     * @param    RadioField             $parent
     * @param    int                    $index
     * @param    string                 $label
     * @param    string                 $value
     * @param    bool                   $checked
     * @param    string|null            $figure
     * @param    ExtraInputType|null    $extra_input_type
     * @param    string|null            $extra_input_name
     * @param    string|null            $extra_input_value
     */
    public function __construct(
        Step       $step,
        RadioField $parent,
        $index,
        $label,
        $value,
        $checked = false,
        $figure = null,
        $extra_input_type  = null,
        $extra_input_name  = null,
        $extra_input_value = null,
        $extra_input_placeholder = null
    )
    {
        $this->template  = new Template(self::TEMPLATES_DIR);

        $this->step                    = $step;
        $this->parent                  = $parent;
        $this->index                   = $index;
        $this->label                   = $label;
        $this->value                   = $value;
        $this->checked                 = $checked;
        $this->figure                  = $figure;
        $this->extra_input_type        = $extra_input_type;
        $this->extra_input_name        = $extra_input_name;
        $this->extra_input_value       = $extra_input_value;
        $this->extra_input_placeholder = $extra_input_placeholder;
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
    protected function _checked()
    {
        return $this->checked;
    }

    /**
     * @return    string|null
     */
    protected function _figure()
    {
        return $this->figure;
    }

    /**
     * @return    ExtraInputType|null
     */
    protected function _extra_input_type()
    {
        return $this->extra_input_type;
    }

    /**
     * @return    string|null
     */
    protected function _extra_input_name()
    {
        return $this->extra_input_name;
    }

    /**
     * @return    string|null
     */
    protected function _extra_input_value()
    {
        return $this->extra_input_value;
    }

    /**
     * @return    string|null
     */
    protected function _extra_input_placeholder()
    {
        return $this->extra_input_placeholder;
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
        switch ($this->extra_input_type) {
            case ExtraInputType::TEXT:
                return $this->template->render(
                    "radio.input.extra_input.text",
                    [
                        "field"  => $this->parent,
                        "option" => $this,
                    ]
                );

            case ExtraInputType::TEXTAREA:
                return $this->template->render(
                    "radio.input.extra_input.textarea",
                    [
                        "field"  => $this->parent,
                        "option" => $this,
                    ]
                );

            default:
                return $this->template->render(
                    "radio.input",
                    [
                        "field"  => $this->parent,
                        "option" => $this,
                    ]
                );
        }
    }
}
