<?php
declare(strict_types=1);

namespace QMS3\Brick\Structure;

use ArrayAccess;
use ArrayIterator;
use Countable;
use IteratorAggregate;
use QMS3\Brick\Structure\StructureRow;


class Structure implements ArrayAccess, Countable, IteratorAggregate
{
    /** @var    array<string,StructureRow> */
    private $rows;

    /**
     * @param    array<string,StructureRow>    $structure_rows
     */
    public function __construct(array $structure_rows)
    {
        assert(
            !empty($structure_rows),
            "\$structure_rows は空ではいけません"
        );

        assert(
            array_filter(array_keys($structure_rows), "is_int") == false,
            "\$structure_rows の全てのキーは文字列でなければいけません"
        );

        $this->rows = $structure_rows;
    }

    // ====================================================================== //

    /**
     * @param     int             $index
     * @return    StructureRow
     */
    public function index($index)
    {
        return array_values($this->rows)[$index];
    }

    // ====================================================================== //

    /**
     * @param     string     $column_name
     * @return    mixed[]
     */
    public function column($column_name)
    {
        $values = [];
        foreach ($this->rows as $structure_row) {
            $values[] = $structure_row->$column_name;
        }

        return $values;
    }

    /**
     * @param     callable    $callback
     * @return    self
     */
    public function filter(callable $callback)
    {
        $rows = array_filter($this->rows, $callback);

        return new self($rows);
    }

    /**
     * @param     callable    $callback
     * @return    Structure[]
     */
    public function group_by(callable $callback)
    {
        $groups = [];
        foreach ($this->rows as $name => $structure_row) {
            $key = $callback($structure_row, $name);

            if (!isset($groups[$key])) { $groups[$key] = []; }

            $groups[$key][$name] = $structure_row;
        }

        $structures = [];
        foreach ($groups as $key => $structure_rows) {
            $structures[$key] = new self($structure_rows);
        }

        return $structures;
    }

    /**
     * @param     callable    $callback
     * @return    mixed[]
     */
    public function map(callable $callback)
    {
        return array_map($callback, $this->rows);
    }

    /**
     * @param     int         $offset
     * @param     int|null    $length
     * @return    self
     */
    public function slice($offset, $length = null)
    {
        return is_null($length)
            ? new self (array_slice($this->rows, $offset))
            : new self (array_slice($this->rows, $offset, $length));
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
     * @param     int|string      $offset
     * @return    StructureRow
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

    // ====================================================================== //

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
     * @return    ArrayIterator<string,StructureRow>
     */
    #[\ReturnTypeWillChange]
    public function getIterator()
    {
        return new ArrayIterator($this->rows);
    }
}
