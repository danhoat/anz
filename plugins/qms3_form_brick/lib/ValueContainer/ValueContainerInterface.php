<?php
declare(strict_types=1);

namespace QMS3\Brick\ValueContainer;

use IteratorAggregate;


interface ValueContainerInterface extends IteratorAggregate
{
    /**
     * @return    string
     */
    public function __toString();
}
