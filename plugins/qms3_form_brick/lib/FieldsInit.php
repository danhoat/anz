<?php
declare(strict_types=1);

namespace QMS3\Brick;

use QMS3\Brick\Field\Field;
use QMS3\Brick\FieldFactory\FieldFactory;
use QMS3\Brick\Step\Step;
use QMS3\Brick\Structure\Structure;
use QMS3\Brick\Values\Values;


/**
 * @since    1.5.2
 */
class FieldsInit
{
    /**
     * @param     Structure    $structure
     * @param     Values       $values
     * @param     Step         $step
     * @return    array<string,Field>
     */
    public function init(Structure $structure, Values $values, Step $step)
    {
        $factory = new FieldFactory();

        $fields = [];
        foreach ($structure as $name => $structure_row) {
            $fields[$name] = $factory->create($structure_row, $values, $step);
        }

        return $fields;
    }
}
