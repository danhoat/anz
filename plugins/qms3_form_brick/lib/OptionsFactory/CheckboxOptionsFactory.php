<?php
declare(strict_types=1);

namespace QMS3\Brick\OptionsFactory;

use QMS3\Brick\Field\CheckboxField;
use QMS3\Brick\Option\CheckboxOption;
use QMS3\Brick\OptionsParser\CheckboxOptionsParser;
use QMS3\Brick\Step\Step;
use QMS3\Brick\Values\Values;
use QMS3\Brick\ValueContainer\CheckboxValueContainer;


class CheckboxOptionsFactory
{
    /** @var    CheckboxField */
    private $parent;

    /** @var    Values */
    private $values;

    /** @var    Step */
    private $step;

    public function __construct(
        CheckboxField $parent,
        Values        $values,
        Step          $step
    )
    {
        $this->parent = $parent;
        $this->values = $values;
        $this->step   = $step;
    }

    /**
     * @param     string              $src
     * @return    CheckboxOption[]
     */
    public function create($src)
    {
        $parser = new CheckboxOptionsParser();
        $results = $parser->parse($src);

        $name  = $this->parent->name;

        /** @var    CheckboxValueContainer */
        $value_items = $this->values[$name] ?: [];

        $value = [];
        foreach ($value_items as $value_item) {
            $value[] = $value_item->value;
        }

        $options = [];
        foreach ($results as $index => $result) {
            $options[] = new CheckboxOption(
                /* $step                    = */ $this->step,
                /* $parent                  = */ $this->parent,
                /* $index                   = */ $index,
                /* $label                   = */ $result->get_label(),
                /* $value                   = */ $result->get_value(),
                /* $checked                 = */ in_array($result->get_value(), $value, /* $strict = */ true),
                /* $figure                  = */ $result->get_figure(),
                /* $extra_input_type        = */ $result->get_extra_input_type(),
                /* $extra_input_name        = */ $result->get_extra_input_name(),
                /* $extra_input_value       = */ $result->get_extra_input_value(),
                /* $extra_input_placeholder = */ $result->get_extra_input_placeholder()
            );
        }

        return $options;
    }
}
