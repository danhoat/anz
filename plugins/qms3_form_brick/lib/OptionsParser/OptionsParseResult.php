<?php
declare(strict_types=1);

namespace QMS3\Brick\OptionsParser;

use QMS3\Brick\Enum\ExtraInputType;


class OptionsParseResult
{
    /** @var    string */
    private $label;

    /** @var    string */
    private $value;

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
     * @param    string    $label
     * @param    string    $value
     * @param    string|null    $figure
     * @param    ExtraInputType|null    $extra_input_type
     * @param    string|null    $extra_input_name
     * @param    string|null    $extra_input_value
     * @param    string|null    $extra_input_placeholder
     */
    public function __construct(
        $label,
        $value,
        $figure = null,
        $extra_input_type = null,
        $extra_input_name = null,
        $extra_input_value = null,
        $extra_input_placeholder = null
    )
    {
        $this->label = $label;
        $this->value = $value;
        $this->figure = $figure;
        $this->extra_input_type = $extra_input_type;
        $this->extra_input_name = $extra_input_name;
        $this->extra_input_value = $extra_input_value;
        $this->extra_input_placeholder = $extra_input_placeholder;
    }

    /**
     * @return    string
     */
    public function get_label()
    {
        return $this->label;
    }

    /**
     * @return    string
     */
    public function get_value()
    {
        return $this->value;
    }

    /**
     * @return    string|null
     */
    public function get_figure()
    {
        return $this->figure;
    }

    /**
     * @return    ExtraInputType|null
     */
    public function get_extra_input_type()
    {
        return $this->extra_input_type;
    }

    /**
     * @return    string|null
     */
    public function get_extra_input_name()
    {
        return $this->extra_input_name;
    }

    /**
     * @return    string|null
     */
    public function get_extra_input_value()
    {
        return $this->extra_input_value;
    }

    /**
     * @return    string|null
     */
    public function get_extra_input_placeholder()
    {
        return $this->extra_input_placeholder;
    }

    // ====================================================================== //

    /**
     * @return    array<string,mixed>
     */
    public function to_array()
    {
        return [
            'label' => $this->label,
            'value' => $this->value,
            'figure' => $this->figure,
            'extra_input_type' => $this->extra_input_type
                ? $this->extra_input_type->value()
                : null,
            'extra_input_name' => $this->extra_input_name,
            'extra_input_value' => $this->extra_input_value,
            'extra_input_placeholder' => $this->extra_input_placeholder,
        ];
    }
}
