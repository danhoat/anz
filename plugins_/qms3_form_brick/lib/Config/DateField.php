<?php
declare(strict_types=1);

namespace QMS3\Brick\Config;

use DateTime;
use Exception;
use LogicException;
use Detection\MobileDetect;
use QMS3\Brick\Param\Param;
use QMS3\Brick\Config\ConfigInterface;
use QMS3\Brick\Step\Step;
use QMS3\Brick\Structure\Structure;
use QMS3\Brick\Values\Values;


class DateField implements ConfigInterface
{
    /** @var    string */
    private $name;

    /** @var    string */
    private $format;

    /**
     * @param    string    $name
     * @param    string    $format
     */
    public function __construct($name, $format = "Y-m-d")
    {
        $this->name   = $name;
        $this->format = $format;
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

        $value = $values[$this->name];

        if ($structure[$this->name]->type == "datepicker") {
            return $value->valid_date
                ? $value->date->format($this->format)
                : "";
        }

        $value = (string) $value;

        if (trim($value) == false) { return ""; }

        try {
            $date = new DateTime($value);
            return $date->format($this->format);
        } catch (Exception $e) {
            return "";
        }
    }
}
