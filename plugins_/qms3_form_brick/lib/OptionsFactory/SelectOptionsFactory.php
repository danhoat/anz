<?php
declare(strict_types=1);

namespace QMS3\Brick\OptionsFactory;

use QMS3\Brick\Field\SelectField;
use QMS3\Brick\Option\SelectOption;
use QMS3\Brick\OptionsParser\SelectOptionsParser;
use QMS3\Brick\Step\Step;
use QMS3\Brick\Values\Values;


class SelectOptionsFactory
{
    /** @var    SelectField */
    private $parent;

    /** @var    Values */
    private $values;

    /** @var    Step */
    private $step;

    public function __construct(
        SelectField $parent,
        Values      $values,
        Step        $step
    )
    {
        $this->parent = $parent;
        $this->values = $values;
        $this->step   = $step;
    }

    /**
     * @param     string            $src
     * @return    SelectOption[]
     */
    public function create($src)
    {
        $parser = new SelectOptionsParser();
        $results = $parser->parse($src);

        $name  = $this->parent->name;
        $value = $this->values[$name];

        $options = [];
        foreach ($results as $index => $result) {
            $options[] = new SelectOption(
                /* $step     = */ $this->step,
                /* $parent   = */ $this->parent,
                /* $index    = */ $index,
                /* $label    = */ $result->get_label(),
                /* $value    = */ $result->get_value(),
                /* $selected = */ $result->get_value() == $value->value->value
            );
        }

        return $options;
    }
}
