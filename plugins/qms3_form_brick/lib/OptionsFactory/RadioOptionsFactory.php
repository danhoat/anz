<?php
declare(strict_types=1);

namespace QMS3\Brick\OptionsFactory;

use QMS3\Brick\Field\RadioField;
use QMS3\Brick\Option\RadioOption;
use QMS3\Brick\OptionsParser\RadioOptionsParser;
use QMS3\Brick\Step\Step;
use QMS3\Brick\Values\Values;


class RadioOptionsFactory
{
    /** @var    RadioField */
    private $parent;

    /** @var    Values */
    private $values;

    /** @var    Step */
    private $step;

    public function __construct(
        RadioField $parent,
        Values     $values,
        Step       $step
    )
    {
        $this->parent = $parent;
        $this->values = $values;
        $this->step   = $step;
    }

    /**
     * @param     string              $src
     * @return    RadioOption[]
     */
    public function create($src)
    {
        $parser = new RadioOptionsParser();
        $results = $parser->parse($src);

        $name  = $this->parent->name;
        $value = $this->values[$name];

        $options = [];
        foreach ($results as $index => $result) {
            $options[] = new RadioOption(
                /* $step              = */ $this->step,
                /* $parent            = */ $this->parent,
                /* $index             = */ $index,
                /* $label             = */ $result->get_label(),
                /* $value             = */ $result->get_value(),
                /* $checked           = */ $result->get_value() == $value->value->value,
                /* $figure            = */ $result->get_figure(),
                /* $extra_input_type  = */ $result->get_extra_input_type(),
                /* $extra_input_name  = */ $result->get_extra_input_name(),
                /* $extra_input_value = */ $result->get_extra_input_value()
            );
        }

        return $options;
    }
}
