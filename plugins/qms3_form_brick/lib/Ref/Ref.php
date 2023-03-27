<?php
declare(strict_types=1);

namespace QMS3\Brick\Ref;

use QMS3\Brick\FieldRef\FieldRefInterface as FieldRef;
use QMS3\Brick\FieldRef\AutokanaFieldRef;
use QMS3\Brick\FieldRef\EqualToFieldRef;
use QMS3\Brick\FieldRef\Zip2addrFieldRef;


class Ref
{
    /** @var    array<string,FieldRef[]> */
    private $refs;

    /**
     * @param    array<string,FieldRef[]>    $refs
     */
    public function __construct(array $refs = [])
    {
        $this->refs = $refs;
    }

    // ====================================================================== //

    /**
     * @param     AutokanaFieldRef    $ref
     * @return    self
     */
    public function add_autokana(AutokanaFieldRef $ref)
    {
        if (!isset($this->refs["autokana"])) {
            $this->refs["autokana"] = [];
        }

        $this->refs["autokana"][] = $ref;

        return $this;
    }

    /**
     * @return    AutokanaFieldRef[]
     */
    public function autokana()
    {
        return isset($this->refs["autokana"])
            ? $this->refs["autokana"]
            : [];
    }

    // ====================================================================== //

    /**
     * @param     EqualToFieldRef    $ref
     * @return    self
     */
    public function add_equal_to(EqualToFieldRef $ref)
    {
        if (!isset($this->refs["equal_to"])) {
            $this->refs["equal_to"] = [];
        }

        $this->refs["equal_to"][] = $ref;

        return $this;
    }

    /**
     * @return    EqualToFieldRef[]
     */
    public function equal_to()
    {
        return isset($this->refs["equal_to"])
            ? $this->refs["equal_to"]
            : [];
    }

    // ====================================================================== //

    /**
     * @param     Zip2addrFieldRef    $ref
     * @return    self
     */
    public function add_zip2addr(Zip2addrFieldRef $ref)
    {
        if (!isset($this->refs["zip2addr"])) {
            $this->refs["zip2addr"] = [];
        }

        $this->refs["zip2addr"][] = $ref;

        return $this;
    }

    /**
     * @return    Zip2addrFieldRef[]
     */
    public function zip2addr()
    {
        return isset($this->refs["zip2addr"])
            ? $this->refs["zip2addr"]
            : [];
    }
}
