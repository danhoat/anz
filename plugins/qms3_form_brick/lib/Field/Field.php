<?php
declare(strict_types=1);

namespace QMS3\Brick\Field;

use QMS3\Brick\Ref\Ref;
use QMS3\Brick\RefFactory\RefFactory;
use QMS3\Brick\Step\Step;
use QMS3\Brick\Structure\StructureRow;
use QMS3\Brick\Util\PlainTextSanitizer;
use QMS3\Brick\Util\ReadonlyProps;
use QMS3\Brick\Values\ValuesInterface as Values;
use QMS3\Brick\ValueContainer\ValueContainerInterface as ValueContainer;


/**
 * @property-read    string            $block
 * @property-read    string            $label
 * @property-read    string            $type
 * @property-read    string            $input_type
 * @property-read    string            $name
 * @property-read    ValueContainer    $value
 * @property-read    string            $prepend
 * @property-read    string            $append
 * @property-read    string            $header_notice
 * @property-read    string            $body_notice
 * @property-read    Ref               $ref
 * @property-read    string            $placeholder
 * @property-read    bool              $for_bcc
 * @property-read    bool              $thanks_to
 * @property-read    bool              $required
 * @property-read    string            $attributes
 */
abstract class Field
{
    use ReadonlyProps;

    /** @var    StructureRow */
    protected $structure_row;

    /** @var    Values */
    protected $values;

    /** @var    Step */
    private $step;

    /** @var    PlainTextSanitizer */
    protected $sanitizer;

    /**
     * @return    string
     */
    protected function _block()
    {
        return trim($this->structure_row->block);
    }

    /**
     * @param     bool      $sanitize    HTML タグと改行文字を取り除く
     * @return    string
     */
    protected function _label($sanitize = false)
    {
        $label = trim($this->structure_row->label);

        if ($sanitize) { $label = $this->sanitizer->sanitize($label); }

        return $label;
    }

    /**
     * @return    string
     */
    protected function _type()
    {
        return trim($this->structure_row->type) ?: "text";
    }

    /**
     * <input> タグの type 属性に設定される想定の値
     *
     * ただし、<select>, <textarea> に対応する値として
     * 'select', 'textarea' が返る場合がある
     *
     * また、見出し行に対応する値として 'title' が返る場合がある
     *
     * @return    string
     */
    protected function _input_type()
    {
        switch ($this->type) {
            case "pref": return "select";

            // TODO: もう少しちゃんとする

            default: return $this->type;
        }
    }

    /**
     * @return    string
     */
    protected function _name()
    {
        return trim($this->structure_row->name);
    }

    /** @return    ValueContainer */
    protected function _value()
    {
        return $this->values[$this->name];
    }

    /**
     * @param     bool      $sanitize    HTML タグと改行文字を取り除く
     * @return    string
     */
    protected function _prepend($sanitize = false)
    {
        $prepend =  trim($this->structure_row->prepend);

        if ($sanitize) { $prepend = $this->sanitizer->sanitize($prepend); }

        return $prepend;
    }

    /**
     * @param     bool      $sanitize    HTML タグと改行文字を取り除く
     * @return    string
     */
    protected function _append($sanitize = false)
    {
        $append = trim($this->structure_row->append);

        if ($sanitize) { $append = $this->sanitizer->sanitize($append); }

        return $append;
    }

    /**
     * @param     bool      $sanitize    HTML タグと改行文字を取り除く
     * @return    string
     */
    protected function _header_notice($sanitize = false)
    {
        $notice = trim($this->structure_row->header_notice);

        if ($sanitize) { $notice = $this->sanitizer->sanitize($notice); }

        return $notice;
    }

    /**
     * @param     bool      $sanitize    HTML タグと改行文字を取り除く
     * @return    string
     */
    protected function _body_notice($sanitize = false)
    {
        $notice = trim($this->structure_row->body_notice);

        if ($sanitize) { $notice = $this->sanitizer->sanitize($notice); }

        return $notice;
    }

    /**
     * @return    Ref
     */
    protected function _ref()
    {
        $factory = new RefFactory();
        return $factory->create($this->structure_row->options);
    }

    /**
     * @return    string
     */
    protected function _placeholder()
    {
        return trim($this->structure_row->placeholder);
    }

    /**
     * @return    bool
     */
    protected function _for_bcc()
    {
        return $this->structure_row->for_bcc;
    }

    /**
     * @return    bool
     */
    protected function _thanks_to()
    {
        return $this->structure_row->thanks_to;
    }

    /**
     * @return    bool
     */
    protected function _required()
    {
        return $this->structure_row->required;
    }

    /**
     * @return    string
     */
    protected function _attributes()
    {
        return trim($this->structure_row->attributes);
    }

    // ====================================================================== //

    /**
     * @param     string|null    $step
     * @return    string
     */
    public function render($step = null)
    {
        $step = is_string($step) ? strtoupper($step) : $this->step->value();

        switch ($step) {
            case Step::INPUT  : return $this->render_input();
            case Step::CONFIRM: return $this->render_confirm();
            case Step::RESULT : return $this->render_result();
            case Step::HIDDEN : return $this->render_hidden();
            case Step::SUBMIT:
            case "PLAIN":
                return $this->render_plain();

            default: throw new \Exception();
        }
    }

    /**
     * @return    string
     */
    abstract protected function render_input();

    /**
     * @return    string
     */
    abstract protected function render_confirm();

    /**
     * @return    string
     */
    abstract protected function render_result();

    /**
     * @return    string
     */
    abstract protected function render_hidden();

    /**
     * @return    string
     */
    abstract protected function render_plain();
}
