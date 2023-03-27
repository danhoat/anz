<?php
declare(strict_types=1);

namespace QMS3\Brick\Field;

use QMS3\Brick\Field\Field;
use QMS3\Brick\Ref\Ref;
use QMS3\Brick\Step\Step;
use QMS3\Brick\Structure\StructureRow;
use QMS3\Brick\Values\ValuesInterface as Values;
use QMS3\Brick\Util\PlainTextSanitizer;
use QMS3\Brick\Util\Template;


/**
 * @property-read    string    $block
 * @property-read    string    $label
 * @property-read    string    $type
 * @property-read    string    $input_type
 * @property-read    string    $name
 * @property-read    mixed     $value
 * @property-read    string    $prepend
 * @property-read    string    $append
 * @property-read    string    $header_notice
 * @property-read    string    $body_notice
 * @property-read    Ref       $ref
 * @property-read    string    $placeholder
 * @property-read    bool      $for_bcc
 * @property-read    bool      $thanks_to
 * @property-read    bool      $required
 * @property-read    string    $attributes
 */
class TitleField extends Field
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
     * @return    string
     */
    protected function render_input()
    {
        return "";
    }

    /**
     * @return    string
     */
    protected function render_confirm()
    {
        return "";
    }

    /**
     * @return    string
     */
    protected function render_result()
    {
        return "";
    }

    /**
     * @return    string
     */
    protected function render_hidden()
    {
        return "";
    }

    /**
     * @return    string
     */
    protected function render_plain()
    {
        return "";
    }
}
