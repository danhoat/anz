<?php
declare(strict_types=1);

namespace QMS3\Brick\Field;

use QMS3\Brick\Field\Field;
use QMS3\Brick\Option\RadioOption;
use QMS3\Brick\OptionsFactory\RadioOptionsFactory;
use QMS3\Brick\Ref\Ref;
use QMS3\Brick\Step\Step;
use QMS3\Brick\Structure\StructureRow;
use QMS3\Brick\Util\PlainTextSanitizer;
use QMS3\Brick\Util\Template;
use QMS3\Brick\Values\ValuesInterface as Values;


/**
 * @property-read    string            $block
 * @property-read    string            $label
 * @property-read    string            $type
 * @property-read    string            $input_type
 * @property-read    string            $name
 * @property-read    mixed             $value
 * @property-read    string            $prepend
 * @property-read    string            $append
 * @property-read    string            $header_notice
 * @property-read    string            $body_notice
 * @property-read    Ref               $ref
 * @property-read    RadioOption[]     $options
 * @property-read    string            $placeholder
 * @property-read    bool              $for_bcc
 * @property-read    bool              $thanks_to
 * @property-read    bool              $required
 * @property-read    string            $attributes
 */
class RadioField extends Field
{
    const TEMPLATES_DIR = __DIR__ . "/../../templates/field";

    /** @var    StructureRow */
    protected $structure_row;

    /** @var    Values */
    protected $values;

    /** @var    Template */
    protected $template;

    /** @var    Step */
    protected $step;

    /** @var    PlainTextSanitizer */
    protected $sanitizer;

    public function __construct(
        StructureRow       $structure_row,
        Values             $values,
        Step               $step,
        PlainTextSanitizer $sanitizer
    )
    {
        $this->structure_row = $structure_row;
        $this->values        = $values;
        $this->step          = $step;
        $this->sanitizer     = $sanitizer;

        $this->template = new Template(self::TEMPLATES_DIR);
    }

    // ====================================================================== //

    /**
     * @return    RadioOption[]
     */
    protected function _options()
    {
        $factory = new RadioOptionsFactory($this, $this->values, $this->step);

        return $factory->create($this->structure_row->options);
    }

    // ====================================================================== //

    /**
     * @return    string
     */
    protected function render_input()
    {
        return $this->template->render(
            "radio.input",
            [
                "field" => $this,
            ]
        );
    }

    /**
     * @return    string
     */
    protected function render_confirm()
    {
        return $this->template->render(
            $this->value ? "radio.confirm" : "radio.confirm.empty",
            [
                "field"  => $this,
                "hidden" => $this->render_hidden(),
            ]
        );
    }

    /**
     * @return    string
     */
    protected function render_result()
    {
        return $this->template->render(
            $this->value ? "radio.confirm" : "radio.confirm.empty",
            [
                "field"  => $this,
                "hidden" => null,
            ]
        );
    }

    /**
     * @return    string
     */
    protected function render_hidden()
    {
        return "<input type=\"hidden\" name=\"{$this->name}\" value=\"{$this->value}\">";
    }

    /**
     * @return    string
     */
    protected function render_plain()
    {
        if (!$this->value) { return ""; }

        $texts = [
            $this->prepend,
            $this->value,
            $this->append,
        ];

        return join(" ", array_filter($texts));
    }
}
