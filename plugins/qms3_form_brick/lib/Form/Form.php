<?php
declare(strict_types=1);

namespace QMS3\Brick\Form;

use ArrayAccess;
use ArrayIterator;
use Countable;
use IteratorAggregate;
use LogicException;
use QMS3\Brick\Action\Action;
use QMS3\Brick\Block\Block;
use QMS3\Brick\Button\Buttons;
use QMS3\Brick\Field\Field;
use QMS3\Brick\Flow\Flow;
use QMS3\Brick\HealthCheck\HealthCheck;
use QMS3\Brick\HealthCheckWarning\HealthCheckWarning;
use QMS3\Brick\Metadata\Metadata;
use QMS3\Brick\Step\Step;
use QMS3\Brick\Util\ReadonlyProps;
use QMS3\Brick\Values\ValuesInterface as Values;


/**
 * @property-read    array<string,Block>    $all_blocks
 * @property-read    array<string,Block>    $blocks
 * @property-read    array<string,Field>    $fields
 *
 * @property-read    string      $form_name
 * @property-read    Flow        $flow
 * @property-read    Action      $action
 * @property-read    string      $enctype
 * @property-read    Buttons     $buttons
 * @property-read    Metadata    $metadata
 *
 * @method    array<string,Block>     blocks(...$block_names)
 * @method    array<string,Field>     fields(...$field_names)
 *
 * @method    string      form_name()
 * @method    Flow        flow($steps = null)
 * @method    Action      action($step = null)
 * @method    string      enctype()
 * @method    Buttons     buttons()
 * @method    Metadata    metadata()
 */
class Form implements ArrayAccess, Countable, IteratorAggregate
{
    use ReadonlyProps;

    /** @var    string */
    private $form_name;

    /** @var    array<string,Field> */
    private $fields;

    /** @var    Values */
    private $values;

    /** @var    Metadata */
    private $metadata = null;

    /** @var    Step */
    private $step;

    /** @var    Block[] */
    private $_all_blocks = null;

    /**
     * @param    string                 $form_name
     * @param    array<string,Field>    $fields
     * @param    Values                 $values
     * @param    Step                   $step
     */
    public function __construct(
        $form_name,
        array    $fields,
        Values   $values,
        Metadata $metadata,
        Step     $step
    )
    {
        assert(count($fields) > 0);

        $this->form_name = trim($form_name);
        $this->fields    = $fields;
        $this->values    = $values;
        $this->metadata  = $metadata;
        $this->step      = $step;
    }

    // ====================================================================== //

    /**
     * @return    Block[]
     */
    protected function _all_blocks()
    {
        if (!is_null($this->_all_blocks)) { return $this->_all_blocks; }

        /** @var    array<string,Field[]> */
        $fields_bucket = [];
        foreach ($this->fields as $field) {
            if (!isset($fields_bucket[$field->block])) {
                $fields_bucket[$field->block] = [];
            }

            $fields_bucket[$field->block][] = $field;
        }

        $blocks = [];
        foreach ($fields_bucket as $block_name => $fields) {
            $blocks[$block_name] = new Block($fields, $this->values, $this->step);
        }

        $this->_all_blocks = $blocks;
        return $this->_all_blocks;
    }

    /**
     * @param     string[]    ...$block_names
     * @return    Block[]
     */
    protected function _blocks(...$block_names)
    {
        if (empty($block_names)) { return $this->all_blocks; }

        $blocks = [];
        foreach ($this->all_blocks as $block_name => $block) {
            if (in_array($block_name, $block_names, /* $strict = */ true)) {
                $blocks[$block_name] = $block;
            }
        }

        return $blocks;
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
     * @return    string
     */
    protected function _form_name()
    {
        return $this->form_name;
    }

    /**
     * @param     int|string[]|null    $steps
     * @return    Flow
     */
    protected function _flow($steps = null)
    {
        return new Flow($this->step, $steps ?: 3);
    }

    /**
     * @param     string|null    $step
     * @return    Action
     */
    protected function _action($step = null)
    {
        $step = is_null($step) ? $this->step : new Step($step);

        return new Action($step);
    }

    /**
     * <form> の enctype 属性に設定するべき値
     *
     * <form> の中に <input type="file"> が存在しているときは
     * enctype="multipart/form-data" とするべき
     * さもなければ enctype="application/x-www-form-urlencoded" とする
     *
     * @see    https://developer.mozilla.org/ja/docs/Web/HTML/Element/form#attr-enctype
     *
     * @param     string[]    ...$block_names
     * @return    string
     */
    protected function _enctype(...$block_names)
    {
        /** @var    Block[] */
        $blocks = call_user_func_array([$this, "blocks"], $block_names);

        $file_field_exists = false;
        foreach ($blocks as $block) {
            foreach ($block->fields as $field) {
                if ($field->type == "file") {
                    $file_field_exists = true;
                }
            }
        }

        return $file_field_exists
            ? "multipart/form-data"
            : "application/x-www-form-urlencoded";
    }

    /**
     * @param     string|null    $step
     * @return    Buttons
     */
    protected function _buttons($step = null)
    {
        $step = is_null($step) ? $this->step : new Step($step);

        return new Buttons($step);
    }

    /**
     * @return    Metadata
     */
    protected function _metadata()
    {
        return $this->metadata;
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
        return isset($this->blocks[$offset]);
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
        return $this->blocks[$offset];
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
        return count($this->blocks);
    }

    // ====================================================================== //

    // IteratorAggregate

    /**
     * @return    ArrayIterator<Block>
     */
    #[\ReturnTypeWillChange]
    public function getIterator()
    {
        return new ArrayIterator($this->blocks);
    }

    // ====================================================================== //

    /**
     * @return    HealthCheckWarning[]
     */
    public function health_check()
    {
        $doctor = new HealthCheck($this);
        return $doctor->check();
    }
}
