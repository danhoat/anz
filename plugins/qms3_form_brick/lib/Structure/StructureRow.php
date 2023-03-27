<?php
declare(strict_types=1);

namespace QMS3\Brick\Structure;

use QMS3\Brick\Util\ReadonlyProps;


/**
 * @property-read    string    $block            ブロック指定
 * @property-read    string    $label            行ラベル
 * @property-read    string    $type             type 属性
 * @property-read    string    $name             name 属性
 * @property-read    mixed     $default_value    value 属性のデフォルト値
 * @property-read    string    $prepend          前述文字
 * @property-read    string    $append           後述文字
 * @property-read    string    $header_notice    ヘッダー注釈
 * @property-read    string    $body_notice      注釈
 * @property-read    string    $options          オプション指定
 * @property-read    string    $placeholder      placeholder 属性
 * @property-read    bool      $for_bcc          社内通知メールに内容記載するかどうか
 * @property-read    bool      $thanks_to        入力値をサンクスメールの宛先として使用するかどうか
 * @property-read    bool      $required         必須かどうか
 * @property-read    string    $attributes       追加の属性指定
 */
class StructureRow
{
    use ReadonlyProps;

    /** @var    string[] */
    private $row;

    /** @var    string */
    private $_name = null;

    /**
     * @param    mixed[]    $row
     */
    public function __construct(array $structure_table_row)
    {
        $this->row = $structure_table_row;
    }

    // ====================================================================== //

    /**
     * @return    string
     */
    protected function _block()
    {
        return trim($this->row["block"] ?: "");
    }

    /**
     * @return    string
     */
    protected function _label()
    {
        return trim($this->row["label"] ?: "");
    }

    /**
     * @return    string
     */
    protected function _type()
    {
        return trim($this->row["type"] ?: "");
    }

    /**
     * @return    string
     */
    protected function _name()
    {
        if (!is_null($this->_name)) { return $this->_name; }

        // type が 'title' のときには name 属性が空文字になることがある
        // そのときはハッシュ関数を使って適当に name を生成する
        $name = trim($this->row["name"]);
        if ($this->type == "title" && empty($name)) {
            $hash = hash(
                "crc32",
                json_encode([$this->block, $this->label])
            );
            $name = "_{$hash}";
        }

        // type が 'title' ではないときに name 属性が空文字になるのは不正です
        // しかし name 属性のバリデーションは別の場所で行うので
        // いったん空文字であってもそのまま返していい

        return $name;
    }

    /**
     * @return    mixed
     */
    protected function _default_value()
    {
        $multiple_value_types = [ "checkbox" ];

        $default_value = trim($this->row["default"]);

        if (in_array($this->type, $multiple_value_types, /* $strict = */ true)) {
            if ($default_value) {
                $values = array_map("trim", preg_split("/[\s,]+/", $default_value));
                return array_filter($values);
            } else {
                return [];
            }
        } else {
            if ($default_value) {
                return $default_value;
            } else {
                return "";
            }
        }
    }

    /**
     * @return    string
     */
    protected function _prepend()
    {
        return trim($this->row["prepend"] ?: "");
    }

    /**
     * @return    string
     */
    protected function _append()
    {
        return trim($this->row["append"] ?: "");
    }

    /**
     * @return    string
     */
    protected function _header_notice()
    {
        return trim($this->row["header_notice"] ?: "");
    }

    /**
     * @return    string
     */
    protected function _body_notice()
    {
        return trim($this->row["body_notice"] ?: "");
    }

    /**
     * @return    string
     */
    protected function _options()
    {
        return trim($this->row["options"]);
    }

    /**
     * @return    string
     */
    protected function _placeholder()
    {
        return trim($this->row["placeholder"] ?: "");
    }

    /**
     * @return    bool
     */
    protected function _for_bcc()
    {
        return is_string($this->row["for_bcc"])
            ? (bool) trim($this->row["for_bcc"])
            : (bool) $this->row["for_bcc"];
    }

    /**
     * @return    bool
     */
    protected function _thanks_to()
    {
        return is_string($this->row["thanks_to"])
            ? (bool) trim($this->row["thanks_to"])
            : (bool) $this->row["thanks_to"];
    }

    /**
     * @return    bool
     */
    protected function _required()
    {
        return is_string($this->row["required"])
            ? (bool) trim($this->row["required"])
            : (bool) $this->row["required"];
    }

    /**
     * @return    string
     */
    protected function _attributes()
    {
        return trim($this->row["attributes"]);
    }

    // ====================================================================== //

    /**
     * @return    array<string,mixed>
     */
    public function to_array()
    {
        return [
            "block"         => $this->block,
            "label"         => $this->label,
            "type"          => $this->type,
            "name"          => $this->name,
            "default_value" => $this->default_value,
            "prepend"       => $this->prepend,
            "append"        => $this->append,
            "header_notice" => $this->header_notice,
            "body_notice"   => $this->body_notice,
            "options"       => $this->options,
            "placeholder"   => $this->placeholder,
            "for_bcc"       => $this->for_bcc,
            "thanks_to"     => $this->thanks_to,
            "required"      => $this->required,
            "attributes"    => $this->attributes,
        ];
    }
}
