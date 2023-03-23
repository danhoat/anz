<?php

namespace Digima\Support;

class Dictionary
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
     * @return array
     */
    public function all()
    {
        return $this->items;
    }

    /**
     * @return int
     */
    public function count()
    {
        return count($this->items);
    }

    /**
     * @param string $key
     * @param mixed $default
     * @return mixed|null
     */
    public function get($key, $default = null)
    {
        if (! array_key_exists($key, $this->items)) {
            return $default;
        }

        return $this->items[$key];
    }

    /**
     * @return bool
     */
    public function isEmpty()
    {
        return $this->count() === 0;
    }

    /**
     * @param $key
     * @return Dictionary
     */
    public function remove($key)
    {
        unset($this->items[$key]);

        return $this;
    }

    /**
     * @param string $key
     * @param mixed $value
     * @return Dictionary
     */
    public function set($key, $value)
    {
        $this->items[$key] = $value;

        return $this;
    }

    /**
     * @param array $items
     * @return Dictionary
     */
    public function setMany(array $items)
    {
        $this->items = array_merge($this->items, $items);

        return $this;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return $this->items;
    }
}
