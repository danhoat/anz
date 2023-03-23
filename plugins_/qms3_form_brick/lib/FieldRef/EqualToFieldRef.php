<?php
declare(strict_types=1);

namespace QMS3\Brick\FieldRef;

use QMS3\Brick\FieldRef\FieldRefInterface;


class EqualToFieldRef implements FieldRefInterface
{
    /** @var    string */
    private $target;

    /**
     * @param    string    $target
     */
    public function __construct($target)
    {
        $this->target   = $target;
    }

    /**
     * @return    string
     */
    public function ref_to()
    {
        return $this->target;
    }

    /**
     * @return    array<string,mixed>
     */
    public function data()
    {
        return [];
    }
}
