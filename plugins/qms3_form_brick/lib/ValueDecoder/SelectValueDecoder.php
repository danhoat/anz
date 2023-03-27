<?php
declare(strict_types=1);

namespace QMS3\Brick\ValueDecoder;

use QMS3\Brick\OptionsParser\OptionsParseResult;
use QMS3\Brick\OptionsParser\SelectOptionsParser;
use QMS3\Brick\Structure\StructureRow;
use QMS3\Brick\ServerRequest\ServerRequest;
use QMS3\Brick\ValueContainer\SelectValueContainer;
use QMS3\Brick\ValueDecoder\ValueDecoderInterface;
use QMS3\Brick\ValueItem\SelectValueItem;


class SelectValueDecoder implements ValueDecoderInterface
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
     * @return    SelectValueContainer
     */
    public function decode(ServerRequest $request, array $param_values)
    {
        $name = $this->structure_row->name;

        if ($request->has($name)) {
            $value = trim($request->get($name));
        } else if (isset($param_values[$name])) {
            $value = $param_values[$name];
        } else {
            $value = $this->structure_row->default_value;
        }

        $parser  = new SelectOptionsParser();
        $options = $parser->parse($this->structure_row->options);

        list($index, $option) = $this->find($options, $value);

        if ($option) {
            $value_item = new SelectValueItem(
                /* $index = */ $index,
                /* $label = */ $option->get_label(),
                /* $value = */ $option->get_value()
            );

            return new SelectValueContainer(
                /* $item       = */ $value_item,
                /* $is_default = */ !$request->has($name)
            );
        } else {
            // 送られてきた値 または デフォルト値が
            // Structure Table の option 列に存在しない場合

            $value_item = new SelectValueItem(
                /* $index = */ -1,
                /* $label = */ $value,
                /* $value = */ $value
            );

            return new SelectValueContainer(
                /* $item       = */ $value_item,
                /* $is_default = */ !$request->has($name)
            );
        }
    }

    /**
     * @param     OptionsParseResult[]    $options_parse_results
     * @param     string                  $value
     * @return    array
     */
    private function find(array $options_parse_results, $value)
    {
        foreach ($options_parse_results as $index => $options_parse_result) {
            if ($options_parse_result->get_value() === $value) {
                return [$index, $options_parse_result];
            }
        }

        return [-1, null];
    }
}
