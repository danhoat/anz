<?php
declare(strict_types=1);

namespace QMS3\Brick\ValueDecoder;

use QMS3\Brick\OptionsParser\CheckboxOptionsParser;
use QMS3\Brick\OptionsParser\OptionsParseResult;
use QMS3\Brick\Structure\StructureRow;
use QMS3\Brick\ServerRequest\ServerRequest;
use QMS3\Brick\ValueContainer\CheckboxValueContainer;
use QMS3\Brick\ValueDecoder\ValueDecoderInterface;
use QMS3\Brick\ValueItem\CheckboxValueItem;


class CheckboxValueDecoder implements ValueDecoderInterface
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
            $value = $request->get($name);

            if (is_array($value)) {
                $values = $value;
            } else if (trim($value) == false) {
                $values = [];
            } else {
                $values = [ trim($value) ];
            }
        } else if (isset($param_values[$name])) {
            $values = $param_values[$name];
        } else {
            $values = $this->structure_row->default_value;
        }

        $parser  = new CheckboxOptionsParser();
        $options = $parser->parse($this->structure_row->options);

        $index_and_option_pairs = $this->find($options, $values);

        $value_items = [];
        foreach ($index_and_option_pairs as list($index, $option)) {
            if ($option instanceof OptionsParseResult) {
                $extra_input_name = $option->get_extra_input_name();

                $extra_input_value = $request->has($extra_input_name)
                    ? trim($request->get($extra_input_name))
                    : "";

                $value_items[] = new CheckboxValueItem(
                    /* $index                   = */ $index,
                    /* $label                   = */ $option->get_label(),
                    /* $value                   = */ $option->get_value(),
                    /* $figure                  = */ $option->get_figure(),
                    /* $extra_input_type        = */ $option->get_extra_input_type(),
                    /* $extra_input_value       = */ $extra_input_value,
                    /* $extra_input_placeholder = */ $option->get_extra_input_placeholder()
                );
            } else {
                // 送られてきた値 または デフォルト値が
                // Structure Table の option 列に存在しない場合

                $value_items[] = new CheckboxValueItem(
                    /* $index                   = */ -1,
                    /* $label                   = */ $option,
                    /* $value                   = */ $option,
                    /* $figure                  = */ null,
                    /* $extra_input_type        = */ null,
                    /* $extra_input_value       = */ null,
                    /* $extra_input_placeholder = */ null
                );
            }
        }

        return new CheckboxValueContainer(
            /* $item       = */ $value_items,
            /* $is_default = */ !$request->has($name)
        );
    }

    /**
     * @param     OptionsParseResult[]    $options_parse_results
     * @param     string[]                $values
     * @return    array
     */
    private function find(array $options_parse_results, array $values)
    {
        $values = array_map("trim", $values);

        $option_values = [];
        foreach ($options_parse_results as $index => $options_parse_result) {
            $option_values[] = $options_parse_result->get_value();
        }

        // TODO: 動いてるけどやばい

        $pairs = [];
        foreach ($values as $value) {
            if (in_array($value, $option_values, /* $strict = */ true)) {
                foreach ($options_parse_results as $index => $options_parse_result) {
                    if ($options_parse_result->get_value() === $value) {
                        $pairs[] = [$index, $options_parse_result];
                    }
                }
            } else {
                $pairs[] = [-1, $value];
            }
        }

        return $pairs;
    }
}
