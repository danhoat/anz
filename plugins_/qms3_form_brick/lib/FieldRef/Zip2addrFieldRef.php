<?php
declare(strict_types=1);

namespace QMS3\Brick\FieldRef;

use QMS3\Brick\FieldRef\FieldRefInterface;


class Zip2addrFieldRef implements FieldRefInterface
{
    /** @var    string */
    private $target;

    /** @var    string */
    private $type;

    /**
     * @param    string    $target
     * @param    bool      $type      値は 'pref', 'addr', 'both' のいずれか
     */
    public function __construct($target, $type)
    {
        $this->target = $target;
        $this->type   = $type;
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
        return [
            "type" => $this->type,
        ];
    }
}
