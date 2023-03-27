<?php
declare(strict_types=1);

namespace QMS3\Brick\Config;

use ArrayIterator;
use IteratorAggregate;
use Detection\MobileDetect;
use QMS3\Brick\Config\ConfigInterface;
use QMS3\Brick\Param\Param;
use QMS3\Brick\Step\Step;
use QMS3\Brick\Structure\Structure;
use QMS3\Brick\Values\Values;


class NestedField implements ConfigInterface, IteratorAggregate
{
    /** @var    array<string,ConfigInterface> */
    private $options;

    /**
     * @param    array<string,ConfigInterface>    $options
     */
    public function __construct(array $options)
    {
        $this->options = $options;
    }

    /**
     * @return    \Iterator<string,ConfigInterface>
     */
    #[\ReturnTypeWillChange]
    public function getIterator()
    {
        return new ArrayIterator($this->options);
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
        $config_values = [];
        foreach ($this->options as $label => $config) {
            $config_values[$label] = $config->get_value(
                $form_type,
                $param,
                $structure,
                $values,
                $step,
                $detect
            );
        }

        return $config_values;
    }
}
