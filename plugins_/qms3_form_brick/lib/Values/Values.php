<?php
declare(strict_types=1);

namespace QMS3\Brick\Values;

use ArrayAccess;
use ArrayIterator;
use Countable;
use IteratorAggregate;
use QMS3\Brick\Values\ValuesInterface;

use QMS3\Brick\ValueContainer\ValueContainerInterface as ValueContainer;


class Values implements ArrayAccess, Countable, IteratorAggregate, ValuesInterface
{
    /** @var    array<string,ValueContainer> */
    private $values;

    /**
     * @param    array<string,ValueContainer>    $values
     */
    public function __construct(array $values)
    {
        $this->values = $values;
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
        return isset($this->values[$offset]);
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
        return $this->values[$offset];
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
        return count($this->values);
    }

    // ====================================================================== //

    // IteratorAggregate

    /**
     * @return    ArrayIterator<Row>
     */
    #[\ReturnTypeWillChange]
    public function getIterator()
    {
        return new ArrayIterator($this->values);
    }
}
