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


class ZipField implements ConfigInterface
{
    /** @var    string */
    private $name;

    /** @var    bool */
    private $remove_hyphen;

    /**
     * @param    string    $name
     * @param    bool      $remove_hyphen
     */
    public function __construct($name, $remove_hyphen = true)
    {
        $this->name          = $name;
        $this->remove_hyphen = $remove_hyphen;
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
        $value = mb_convert_kana($value, "n");  // 全角数字 → 半角数字 変換

        if ($this->remove_hyphen) {
            $value = preg_replace("/[^\d]+/u", "", $value);
        }

        return $value;
    }
}
