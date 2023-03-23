<?php
declare(strict_types=1);

namespace QMS3\Brick\Block;

use ArrayAccess;
use ArrayIterator;
use Countable;
use IteratorAggregate;
use QMS3\Brick\Field\Field;
use QMS3\Brick\Row\Row;
use QMS3\Brick\Step\Step;
use QMS3\Brick\Util\ReadonlyProps;
use QMS3\Brick\Util\Template;
use QMS3\Brick\Values\ValuesInterface as Values;


/**
 * @property-read    string                 $block     ブロック名
 * @property-read    Row[]                  $rows
 * @property-read    array<string,Field>    $fields
 *
 * @method    array<string,Field>    fields(...$field_names)
 */
class Block implements ArrayAccess, Countable, IteratorAggregate
{
    use ReadonlyProps;

    const TEMPLATES_DIR = __DIR__ . "/../../templates/block";

    /** @var    array<string,Field> */
    private $fields;

    /** @var    Values */
    private $values;

    /** @var    Step */
    private $step;

    /** @var    Template */
    private $template;

    /** @var    Row[] */
    private $_rows = null;

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

    // ====================================================================== //

    /**
     * @return    Row[]
     */
    protected function _rows()
    {
        if (!is_null($this->_rows)) { return $this->_rows; }

        /** @var    array<string,Field[]> */
        $fields_bucket = [];
        foreach ($this->fields as $field) {
            if (!isset($fields_bucket[$field->label])) {
                $fields_bucket[$field->label] = [];
            }

            $fields_bucket[$field->label][] = $field;
        }

        $rows = [];
        foreach ($fields_bucket as $fields) {
            $rows[] = new Row($fields, $this->values, $this->step);
        }

        $this->_rows = $rows;
        return $this->_rows;
    }

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
    public function render_input()
    {
        return $this->template->render(
            "input",
            [
                "block" => $this,
            ]
        );
    }

    /**
     * @return    string
     */
    public function render_confirm()
    {
        return $this->template->render(
            "confirm",
            [
                "block" => $this,
            ]
        );
    }

    /**
     * @return    string
     */
    public function render_result()
    {
        return $this->template->render(
            "result",
            [
                "block" => $this,
            ]
        );
    }

    /**
     * @return    string
     */
    public function render_hidden()
    {
        return $this->template->render(
            "hidden",
            [
                "block" => $this,
            ]
        );
    }

    /**
     * @return    string
     */
    public function render_plain()
    {
        return $this->template->render(
            "plain",
            [
                "block" => $this,
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
        return isset($this->rows[$offset]);
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
        return $this->rows[$offset];
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
        return count($this->rows);
    }

    // ====================================================================== //

    // IteratorAggregate

    /**
     * @return    ArrayIterator<Row>
     */
    #[\ReturnTypeWillChange]
    public function getIterator()
    {
        return new ArrayIterator($this->rows);
    }
}
