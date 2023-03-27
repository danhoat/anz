<?php
declare(strict_types=1);

namespace QMS3\Brick\ValueDecoder;

use QMS3\Brick\OptionsParser\OptionsParseResult;
use QMS3\Brick\OptionsParser\RadioOptionsParser;
use QMS3\Brick\Structure\StructureRow;
use QMS3\Brick\ServerRequest\ServerRequest;
use QMS3\Brick\ValueContainer\RadioValueContainer;
use QMS3\Brick\ValueDecoder\ValueDecoderInterface;
use QMS3\Brick\ValueItem\RadioValueItem;


class RadioValueDecoder implements ValueDecoderInterface
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
     * @return    RadioValueContainer
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

        $parser  = new RadioOptionsParser();
        $options = $parser->parse($this->structure_row->options);

        list($index, $option) = $this->find($options, $value);

        if ($option) {
            $extra_input_name = $option->get_extra_input_name();

            $extra_input_value = $request->has($extra_input_name)
                ? trim($request->get($extra_input_name))
                : "";

            $value_item = new RadioValueItem(
                /* $index                   = */ $index,
                /* $label                   = */ $option->get_label(),
                /* $value                   = */ $option->get_value(),
                /* $figure                  = */ $option->get_figure(),
                /* $extra_input_type        = */ $option->get_extra_input_type(),
                /* $extra_input_value       = */ $extra_input_value,
                /* $extra_input_placeholder = */ $option->get_extra_input_placeholder()
            );

            return new RadioValueContainer(
                /* $item       = */ $value_item,
                /* $is_default = */ !$request->has($name)
            );
        } else {
            // 送られてきた値 または デフォルト値が
            // Structure Table の option 列に存在しない場合

            $value_item = new RadioValueItem(
                /* $index                   = */ -1,
                /* $label                   = */ $value,
                /* $value                   = */ $value,
                /* $figure                  = */ null,
                /* $extra_input_type        = */ null,
                /* $extra_input_value       = */ null,
                /* $extra_input_placeholder = */ null
            );

            return new RadioValueContainer(
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
