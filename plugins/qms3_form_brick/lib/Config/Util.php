<?php
declare(strict_types=1);

namespace QMS3\Brick\Config;

use LogicException;
use QMS3\Brick\Config\Callback;
use QMS3\Brick\Config\ConstValue;
use QMS3\Brick\Config\DateField;
use QMS3\Brick\Config\FromField;
use QMS3\Brick\Config\IntField;
use QMS3\Brick\Config\NestedField;
use QMS3\Brick\Config\TelField;
use QMS3\Brick\Config\ZipField;
use QMS3\Brick\Config\ConfigInterface;


class Util
{
    /**
     * @param     mixed         $value
     * @return    ConstValue
     */
    public static function const($value)
    {
        return new ConstValue($value);
    }

    /**
     * Util::const() のエイリアス
     *
     * @param     mixed         $value
     * @return    ConstValue
     */
    public static function value($value)
    {
        return new ConstValue($value);
    }

    /**
     * @param     string[]      ...$names
     * @return    FromFields
     */
    public static function from()
    {
        $names = func_get_args();

        return new FromField("", $names);
    }

    /**
     * @param     string        $separator
     * @param     string[]      ...$names
     * @return    FromFields
     */
    public static function from_with($separator)
    {
        $args  = func_get_args();
        $names = array_slice($args, 1);

        return new FromField($separator, $names);
    }

    /**
     * @param     string    $name
     * @param     string    $format
     * @return    DateField
     */
    public static function date($name, $format = "Y-m-d")
    {
        return new DateField($name, $format);
    }

    /**
     * @param     string       $name
     * @param     bool         $remove_hyphen
     * @return    DateField
     */
    public static function zip($name, $remove_hyphen = true)
    {
        return new ZipField($name, $remove_hyphen);
    }

    /**
     * @param     string       $name
     * @param     bool         $remove_hyphen
     * @return    DateField
     */
    public static function tel($name, $remove_hyphen = true)
    {
        return new TelField($name, $remove_hyphen);
    }

    /**
     * @param     string    $name
     * @param     string    $format
     * @return    IntField
     */
    public static function int($name)
    {
        return new IntField($name);
    }

    /**
     * @param     mixed       $value
     * @return    Callback
     */
    public static function callback(callable $callback)
    {
        return new Callback($callback);
    }

    /**
     * @param     array<string,mixed>    $options
     * @return    NestedField
     */
    public static function nest(array $options)
    {
        $normalized_options = [];
        foreach ($options as $label => $config) {
            $normalized_options[$label] = self::wrap($config);
        }

        return new NestedField($normalized_options);
    }

    // ====================================================================== //

    /**
     * @param    mixed
     */
    public static function wrap($config)
    {
        if ($config instanceof ConfigInterface) { return $config; }

        if (is_string($config)) { return self::from($config); }

        if (is_callable($config)) { return self::callback($config); }

        if (is_array($config)) { return self::nest($config); }

        throw new LogicException("不明な形式です。");
    }
}
