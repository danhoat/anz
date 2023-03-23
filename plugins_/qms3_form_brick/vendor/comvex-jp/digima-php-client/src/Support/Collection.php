<?php

namespace Digima\Support;

class Collection
{
    /**
     * @var array
     */
    private $items;

    /**
     * @param array $items
     * @return void
     */
    public function __construct(array $items = array())
    {
        $this->items = $items;
    }

    /**
     * @return int
     */
    public function count()
    {
        return count($this->items);
    }

    /**
     * @return bool
     */
    public function isEmpty()
    {
        return $this->count() === 0;
    }

    /**
     * @return void
     */
    public function pop()
    {
        array_pop($this->items);
    }

    /**
     * @param mixed $item
     * @return Collection
     */
    public function push($item)
    {
        if (is_array($item)) {
            return $this->pushMany($item);
        }

        $this->items[] = $item;

        return $this;
    }

    /**
     * @param array $items
     * @return Collection
     */
    public function pushMany(array $items)
    {
        foreach ($items as $item) {
            $this->items[] = $item;
        }

        return $this;
    }

    /**
     * @return Collection
     */
    public function removeAll()
    {
        $this->items = array();

        return $this;
    }

    /**
     * @return mixed
     */
    public function shift()
    {
        return array_shift($this->items);
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return $this->items;
    }

    /**
     * @param mixed $item
     * @return Collection
     */
    public function unshift($item)
    {
        if (is_array($item)) {
            return $this->unshiftMany($item);
        }

        array_unshift($this->items, $item);

        return $this;
    }

    /**
     * @param array $items
     * @return Collection
     */
    public function unshiftMany(array $items)
    {
        foreach ($items as $item) {
            array_unshift($this->items, $item);
        }

        return $this;
    }
}
