<?php
declare(strict_types=1);

namespace QMS3\Brick\Util;

use ReflectionClass;
use ReflectionMethod;
use ReflectionProperty;
use QMS3\Brick\Exception\UnknownKeyException;


/**
 * クラスに読み取り専用のプロパティを定義するためのトレイト
 *
 * 名前がアンダースコア "_" から始まる protected メソッドを持っている場合、
 * そのメソッドにプロパティとしてアクセスできるようになる
 *
 * @example
 *     class A
 *     {
 *         use ReadonlyProps;
 *
 *         protected function _today($date_format = "Y-m-d")
 *         {
 *             return date($date_format);
 *         }
 *     }
 *
 *     $a = new A();
 *
 *     // A::$today というようにプロパティアクセスできる
 *     echo $a->today;  // => 1970-01-01
 *
 *     // A::today() というようにメソッドアクセスもできる
 *     echo $a->today("Y/n/j");  // 1970/1/1
 */
trait ReadonlyProps
{
    /**
     * @param     string    $name
     * @return    mixed
     */
    public function __get($name)
    {
        $method_name = "_{$name}";

        if (!method_exists($this, $method_name)) {
            throw new UnknownKeyException($name);
        }

        $method_ref = new ReflectionMethod($this, $method_name);
        if (!$method_ref->isProtected()) {
            throw new UnknownKeyException($name);
        }

        return call_user_func_array([$this, $method_name], []);
    }

    /**
     * @param     string     $name
     * @param     mixed[]    $arguments
     * @return    mixed
     */
    public function __call($name, array $arguments = [])
    {
        $method_name = "_{$name}";

        if (!method_exists($this, $method_name)) {
            throw new UnknownKeyException($name);
        }

        $method_ref = new ReflectionMethod($this, $method_name);
        if (!$method_ref->isProtected()) {
            throw new UnknownKeyException($name);
        }

        return call_user_func_array([$this, $method_name], $arguments);
    }

    /**
     * @return    array<string,mixed>
     */
    public function __debugInfo()
    {
        $class_ref = new ReflectionClass($this);

        $properties = [];

        $property_refs = $class_ref->getProperties(ReflectionProperty::IS_PUBLIC);
        foreach ($property_refs as $property_ref) {
            $name  = $property_ref->getName();
            $properties[$name] = $this->$name;
        }

        $method_refs = $class_ref->getMethods(ReflectionMethod::IS_PROTECTED);
        foreach ($method_refs as $method_ref) {
            $method_name = $method_ref->getName();

            if (!preg_match("/^_[^_].+$/", $method_name)) { continue; }

            $name = substr($method_name, 1);
            $properties[$name] = $this->$name;
        }

        return $properties;
    }
}
