<?php
declare(strict_types=1);

namespace QMS3\Brick\FieldRef;

use QMS3\Brick\FieldRef\FieldRefInterface;


class AutokanaFieldRef implements FieldRefInterface
{
    /** @var    string */
    private $target;

    /** @var    bool */
    private $katakana;

    /**
     * @param    string    $target
     * @param    bool      $katakana
     */
    public function __construct($target, $katakana)
    {
        $this->target   = $target;
        $this->katakana = $katakana;
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
            "katakana" => $this->katakana,
        ];
    }
}
