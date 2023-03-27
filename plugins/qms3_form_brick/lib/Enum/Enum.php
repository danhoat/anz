<?php
declare(strict_types=1);

namespace QMS3\Brick\Enum;

use InvalidArgumentException;
use ReflectionObject;
use ReflectionClassConstant;


abstract class Enum
{
    /** @var    mixed */
    private $scalar;

    /**
     * @param    mixed    $value
     */
    public function __construct($value)
    {
        $ref = new ReflectionObject($this);
        $constants = $ref->getConstants();

        if (!in_array($value, $constants, /* $strict = */ true)) {
            throw new InvalidArgumentException("Value [{$value}] is not defined.");
        }

        $this->scalar = $value;
    }

    /**
     * @return    string
     */
    final public function __toString()
    {
        return (string) $this->scalar;
    }

    // ====================================================================== //

    /**
     * @return    mixed
     */
    final public function value()
    {
        return $this->scalar;
    }

    /**
     * @return    string
     */
    final public function description()
    {
        $objectRef = new ReflectionObject($this);
        $constants = $objectRef->getConstants();

        $constant = array_search($this->scalar, $constants, /* $strict = */ true);

        if (!$constant) { return ""; }

        $constantRef = new ReflectionClassConstant($this, $constant);
        $description = $constantRef->getDocComment() ?: "";

        $description = preg_replace("%^\s*/\*\*\s*%", "", $description) ?: $description;
        $description = preg_replace("%\s*\*/\s*$%", "", $description) ?: $description;
        $description = preg_replace("%^\s*\*\s?%m", "", $description) ?: $description;

        return $description;
    }

    /**
     * @param     string|self    $other
     * @return    bool
     */
    public function is($other)
    {
        $other_str = is_string($other) ? $other : $other->value();
        $other_str = trim($other_str);

        return $this->scalar == $other_str;
    }

    /**
     * @param     string|self    ...$others
     * @return    bool
     */
    public function in(...$others)
    {
        $other_strs = [];
        foreach ($others as $other) {
            $other_str = is_string($other) ? $other : $other->value();
            $other_str = trim($other_str);

            $other_strs[] = $other_str;
        }

        return in_array($this->scalar, $other_strs, /* $strict = */ true);
    }

    /**
     * @param     string[]|self[]    $others
     * @return    bool
     */
    public function in_array(array $others)
    {
        $other_strs = [];
        foreach ($others as $other) {
            $other_str = is_string($other) ? $other : $other->value();
            $other_str = trim($other_str);

            $other_strs[] = $other_str;
        }

        return in_array($this->scalar, $other_strs, /* $strict = */ true);
    }
}
