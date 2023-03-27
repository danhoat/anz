<?php
declare(strict_types=1);

namespace QMS3\Brick\Config;

use LogicException;
use Detection\MobileDetect;
use QMS3\Brick\Param\Param;
use QMS3\Brick\Config\ConfigInterface;
use QMS3\Brick\Step\Step;
use QMS3\Brick\Structure\Structure;
use QMS3\Brick\Values\Values;


class FromField implements ConfigInterface
{
    /** @var    string */
    private $separator;

    /** @var    string[] */
    private $names;

    /**
     * @param    string      $separator
     * @param    string[]    $names
     */
    public function __construct($separator, array $names)
    {
        $this->separator = $separator;
        $this->names     = $names;
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
        $this->check($values);

        $config_values = [];
        foreach ($this->names as $name) {
            $field_type = $structure[$name]->type;

            if ($field_type == "checkbox") {
                $config_values[] = join(",", array_map(function ($value) {
                    return (string) $value;
                }, $values[$name]->value));
            } else if ($field_type == "datepicker") {
                $config_values[] = $values[$name]->valid_date
                    ? $values[$name]->date->format("Y-m-d")
                    : "";
            } else {
                $config_values[] = (string) $values[$name]->value;
            }
        }

        return join($this->separator, $config_values);
    }

    /**
     * @param     Values    $values
     * @return    void
     */
    private function check(Values $values)
    {
        $not_exist_names = [];
        foreach ($this->names as $name) {
            if (!isset($values[$name])) {
                $not_exist_names[] = $name;
            }
        }

        if (!empty($not_exist_names)) {
            throw new LogicException("存在しないフィールドが参照されました: \$name: " . join(", ", $not_exist_names));
        }
    }
}
