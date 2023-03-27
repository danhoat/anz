<?php
declare(strict_types=1);

namespace QMS3\Brick\FieldRef;


interface FieldRefInterface
{
    /** @return    string */
    public function ref_to();

    /** @return    array<string,mixed> */
    public function data();
}
