<?php
declare(strict_types=1);

namespace QMS3\Brick\ValueDecoder;

use QMS3\Brick\Structure\StructureRow;
use QMS3\Brick\ServerRequest\ServerRequest;
use QMS3\Brick\ValueContainer\DatepickerValueContainer;
use QMS3\Brick\ValueDecoder\ValueDecoderInterface;


class DatepickerValueDecoder implements ValueDecoderInterface
{
    /** @var    StructureRow */
    private $structure_row;

    /**
     * @param    StructureRow    $structure_row
     */
    public function __construct(StructureRow $structure_row)
    {
        $this->structure_row = $structure_row;
    }

    /**
     * @param     ServerRequest          $request
     * @param     array<string,mixed>    $param_values
     * @return    DatepickerValueContainer
     */
    public function decode(ServerRequest $request, array $param_values)
    {
        $name = $this->structure_row->name;

        if ($request->has($name)) {
            $value = $request->get($name);
            return new DatepickerValueContainer($value, /* $is_default = */ false);
        } else if (isset($param_values[$name])) {
            $value = $param_values[$name];
            return new DatepickerValueContainer($value, /* $is_default = */ true);
        } else {
            $value = $this->structure_row->default_value;
            return new DatepickerValueContainer($value, /* $is_default = */ true);
        }
    }
}
