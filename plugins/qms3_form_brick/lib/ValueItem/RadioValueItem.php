<?php
declare(strict_types=1);

namespace QMS3\Brick\ValueItem;

use QMS3\Brick\Enum\ExtraInputType;
use QMS3\Brick\Util\ReadonlyProps;


/**
 * @property-read    int                    $index
 * @property-read    string                 $label
 * @property-read    string                 $value
 * @property-read    string|null            $figure
 * @property-read    ExtraInputType|null    $extra_input_type
 * @property-read    string|null            $extra_input_value
 * @property-read    string|null            $extra_input_placeholder
 *
 * @method    int                    index()
 * @method    string                 label()
 * @method    string                 value()
 * @method    string|null            figure()
 * @method    ExtraInputType|null    extra_input_type()
 * @method    string|null            extra_input_value()
 * @method    string|null            extra_input_placeholder()
 */
class RadioValueItem
{
    use ReadonlyProps;

    /** @var    int */
    private $index;

    /** @var    string */
    private $label;

    /** @var    string */
    private $value;

    /** @var    string|null */
    private $figure;

    /** @var    ExtraInputType|null */
    private $extra_input_type;

    /** @var    string|null */
    private $extra_input_value;

    /** @var    string|null */
    private $extra_input_placeholder;

    /**
     * @param    int                    $index
     * @param    string                 $label
     * @param    string                 $value
     * @param    string|null            $figure
     * @param    ExtraInputType|null    $extra_input_type
     * @param    string|null            $extra_input_value
     * @param    string|null            $extra_input_placeholder
     */
    public function __construct(
        $index,
        $label,
        $value,
        $figure = null,
        $extra_input_type  = null,
        $extra_input_value = null,
        $extra_input_placeholder = null
    )
    {
        $this->index                   = $index;
        $this->label                   = $label;
        $this->value                   = $value;
        $this->figure                  = $figure;
        $this->extra_input_type        = $extra_input_type;
        $this->extra_input_value       = $extra_input_value;
        $this->extra_input_placeholder = $extra_input_placeholder;
    }

    /**
     * @return    string
     */
    public function __toString()
    {
        if (is_null($this->extra_input_type)) {
            return (string) $this->value;
        } else if ($this->extra_input_type->is(ExtraInputType::TEXT)) {
            return "{$this->value} ( {$this->extra_input_value} )";
        } else if ($this->extra_input_type->is(ExtraInputType::TEXTAREA)) {
            return "{$this->value} ( {$this->extra_input_value} )";
        }

        return "";
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
}
