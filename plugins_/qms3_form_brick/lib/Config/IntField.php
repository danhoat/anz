<?php
declare(strict_types=1);

namespace QMS3\Brick\Config;

use LogicException;
use Detection\MobileDetect;
use QMS3\Brick\Config\ConfigInterface;
use QMS3\Brick\Param\Param;
use QMS3\Brick\Step\Step;
use QMS3\Brick\Structure\Structure;
use QMS3\Brick\Values\Values;


class IntField implements ConfigInterface
{
    /** @var    string */
    private $name;

    /**
     * @param    string    $name
     */
    public function __construct($name)
    {
        $this->name = $name;
    }

    // ====================================================================== //

    /**
     * @param     Structure       $structure
     * @param     Values          $values
     * @param     string          $form_type
     * @param     Param           $param
     * @param     Step            $step
     * @param     MobileDetect    $detect
     * @return    mixed
     */
    public function get_value(
        Structure $structure,
        Values $values,
        $form_type,
        Param $param,
        Step $step,
        MobileDetect $detect
    )
    {
        if (!isset($values[$this->name])) {
            throw new LogicException("存在しないフィールドが参照されました: \$name: {$this->name}");
        }

        $value = (string) $values[$this->name];

        return is_numeric($value)
            ? (int) $value
            : -1;
    }
}
