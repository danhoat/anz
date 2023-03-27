<?php
declare(strict_types=1);

namespace QMS3\Brick\Row;

use ArrayAccess;
use ArrayIterator;
use Countable;
use IteratorAggregate;
use QMS3\Brick\Field\Field;
use QMS3\Brick\Step\Step;
use QMS3\Brick\Util\ReadonlyProps;
use QMS3\Brick\Util\Template;
use QMS3\Brick\Values\ValuesInterface as Values;


/**
 * @property-read    string                 $block             ブロック名
 * @property-read    string                 $label             行ラベル
 * @property-read    bool                   $required          必須かどうか
 * @property-read    bool                   $hidden            input[type=hidden] のフィールドのみを持っている場合 true になる
 * @property-read    string[]               $header_notices    ヘッダー注釈
 * @property-read    string[]               $body_notices      注釈
 * @property-read    array<string,Field>    $fields
 * @property-read    Field|null             $field             Row に含まれる最初の Field を返す
 */
class Row implements ArrayAccess, Countable, IteratorAggregate
{
    use ReadonlyProps;

    const TEMPLATES_DIR = __DIR__ . "/../../templates/row";

    /** @var    array<string,Field> */
    private $fields;

    /** @var    Values */
    private $values;

    /** @var    Template */
    private $template;

    /**
     * @param    array<string,Field>    $fields
     * @param    Values                 $values
     * @param    Step                   $step
     */
    public function __construct(
        array  $fields,
        Values $values,
        Step   $step
    )
    {
        assert(count($fields) > 0);

        $this->fields = $fields;
        $this->values = $values;
        $this->step   = $step;

        $this->template = new Template(self::TEMPLATES_DIR);
    }

    // ====================================================================== //

    /**
     * @return    string
     */
    protected function _block()
    {
        return $this->fields[0]->block;
    }

    /**
     * @param     bool      $sanitize    HTML タグと改行文字を取り除く
     * @return    string
     */
    protected function _label($sanitize = false)
    {
        $fields = array_values($this->fields);

        return $fields[0]->label($sanitize);
    }

    /**
     * row が必須かどうか
     *
     * 正確には「必須かどうか」という属性は field に対して設定されるものなので、
     * row 単体で「必須かどうか」という属性は無い
     * row の中には複数の field が存在し得るし、必須項目/任意項目は混在し得る
     *
     * ただ、CSS でスタイルをあてるときに row 自体が必須かどうかによって class 属性を
     * 振り分けておくと便利なことが多い
     *
     * 便宜上、複数の field の中に必須項目が一つでも含まれていれば
     * その row は必須であるということにする
     *
     * @return    bool
     */
    protected function _required()
    {
        foreach ($this->fields as $field) {
            if ($field->required) { return true; }
        }

        return false;
    }

    /**
     * Row が input[type=hidden] のフィールドのみを持っているとき true を返す
     *
     * @return    bool
     */
    protected function _hidden()
    {
        foreach ($this->fields as $field) {
            if ($field->type !== "hidden") { return false; }
        }

        return true;
    }

    /**
     * @param     bool        $sanitize    HTML タグと改行文字を取り除く
     * @return    string[]
     */
    protected function _header_notices($sanitize = false)
    {
        $notices = [];
        foreach ($this->fields as $field) {
            $notice = $field->header_notice($sanitize);

            if (!empty($notice)) {
                $notices[] = $notice;
            }
        }

        return $notices;
    }

    /**
     * @param     bool        $sanitize    HTML タグと改行文字を取り除く
     * @return    string[]
     */
    protected function _body_notices($sanitize = false)
    {
        $notices = [];
        foreach ($this->fields as $field) {
            $notice = $field->body_notice($sanitize);

            if (!empty($notice)) {
                $notices[] = $notice;
            }
        }

        return $notices;
    }

    // ====================================================================== //

    /**
     * @param     string[]               ...$field_names
     * @return    array<string,Field>
     */
    protected function _fields(...$field_names)
    {
        if (empty($field_names)) { return $this->fields; }

        $fields = [];
        foreach ($this->fields as $field_name => $field) {
            if (in_array($field_name, $field_names, /* $strict = */ true)) {
                $fields[$field_name] = $field;
            }
        }

        return $fields;
    }

    /**
     * Row に含まれる最初の Field を返す
     *
     * "最初の" とはいっても TitleField などが読み飛ばされることによって
     * 2番目以降の Field が返されることはあり得る
     *
     * @return    Field|null
     */
    protected function _field()
    {
        $ignore_types = [
            "title",
            "checkbox",
            "radio",
            "hidden",
        ];

        foreach ($this->fields as $field) {
            if (in_array($field->type, $ignore_types, /* $strict = */ true)) {
                continue;
            }

            return $field;
        }

        return null;
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
     * 入力画面の表示
     *
     * @return    string
     */
    public function render_input()
    {
        if ($this->hidden) {
            return $this->template->render(
                "input.hidden",
                [
                    "row" => $this,
                ]
            );
        }

        $titles = [];
        $fields = [];
        foreach ($this->fields as $field) {
            if ($field->type == "title") {
                $titles[] = $field;
            } else {
                $fields[] = $field;
            }
        }

        return $this->template->render(
            "input",
            [
                "row"    => $this,
                "titles" => $titles,
                "fields" => $fields,
            ]
        );
    }

    /**
     * 確認画面の表示
     *
     * 確認画面 = 前画面のでの入力結果 + 次ページに引き継ぐための hidden input
     *
     * @return    string
     */
    public function render_confirm()
    {
        if ($this->hidden) {
            return $this->render_hidden();
        }

        $titles = [];
        $fields = [];
        foreach ($this->fields as $field) {
            if ($field->type == "title") {
                $titles[] = $field;
            } else {
                $fields[] = $field;
            }
        }

        return $this->template->render(
            "confirm",
            [
                "row" => $this,
                "titles" => $titles,
                "fields" => $fields,
            ]
        );
    }

    /**
     * 前画面のでの入力結果の表示
     *
     * @return    string
     */
    public function render_result()
    {
        if ($this->hidden) {
            return $this->template->render(
                "result.hidden",
                [
                    "row" => $this,
                ]
            );
        }

        $titles = [];
        $fields = [];
        foreach ($this->fields as $field) {
            if ($field->type == "title") {
                $titles[] = $field;
            } else {
                $fields[] = $field;
            }
        }

        return $this->template->render(
            "result",
            [
                "row" => $this,
                "titles" => $titles,
                "fields" => $fields,
            ]
        );
    }

    /**
     * 前画面での入力値を次ページに引き継ぐための hidden input
     *
     * @return    string
     */
    public function render_hidden()
    {
        $hiddens = [];
        foreach ($this->fields as $field) {
            $hiddens[] = $field->render("hidden");
        }

        return join("\n", $hiddens);
    }

    /**
     * @return    string
     */
    public function render_plain()
    {
        return $this->template->render(
            "plain",
            [
                "row" => $this,
            ]
        );
    }

    // ====================================================================== //

    // ArrayAccess

    /**
     * オブジェクトに配列としてアクセスしたとき、オフセットが存在するかどうか
     *
     * @see https://www.php.net/manual/ja/arrayaccess.offsetexists.php
     *
     * @param     int|string    $offset
     * @return    bool
     */
    #[\ReturnTypeWillChange]
    public function offsetExists($offset)
    {
        return isset($this->fields[$offset]);
    }

    /**
     * オブジェクトに配列としてアクセスしたとき、オフセットを取得する
     *
     * @see https://www.php.net/manual/ja/arrayaccess.offsetget.php
     *
     * @param     int|string    $offset
     * @return    mixed
     */
    #[\ReturnTypeWillChange]
    public function offsetGet($offset)
    {
        return $this->fields[$offset];
    }

    /**
     * オブジェクトに配列としてアクセスしたとき、指定したオフセットに値を設定する
     *
     * @see https://www.php.net/manual/ja/arrayaccess.offsetset.php
     *
     * @param     int|string    $offset
     * @param     mixed         $value
     * @return    void
     */
    #[\ReturnTypeWillChange]
    public function offsetSet($offset, $value)
    {
        throw new \Exception("値は読み取り専用です。");
    }

    /**
     * オブジェクトに配列としてアクセスしたとき、オフセットの設定を解除する
     *
     * @see https://www.php.net/manual/ja/arrayaccess.offsetset.php
     *
     * @param     int|string    $offset
     * @return    bool
     */
    #[\ReturnTypeWillChange]
    public function offsetUnset($offset)
    {
        throw new \Exception("値は読み取り専用です。");
    }

    // ====================================================================== //]

    // Countable

    /**
     * @return    int
     */
    #[\ReturnTypeWillChange]
    public function count()
    {
        return count($this->fields);
    }

    // ====================================================================== //

    // IteratorAggregate

    /**
     * @return    ArrayIterator<Field>
     */
    #[\ReturnTypeWillChange]
    public function getIterator()
    {
        return new ArrayIterator($this->fields);
    }
}
