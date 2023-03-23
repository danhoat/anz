<?php
declare(strict_types=1);

namespace QMS3\Brick\PreProcessContainer;

use Exception;
use Detection\MobileDetect;
use QMS3\Brick\Exception\PreProcessException;
use QMS3\Brick\Logger\ErrorLogger;
use QMS3\Brick\Param\Param;
use QMS3\Brick\PreProcess\PreProcessInterface as PreProcess;
use QMS3\Brick\Step\Step;
use QMS3\Brick\Structure\Structure;
use QMS3\Brick\Values\Values;


/**
 * @since    1.5.2
 */
class PreProcessContainer
{
    /** @var    PreProcess[] */
    private $pre_processes;

    /**
     * @param    PreProcess[]    $pre_processes
     * @param    Param           $param
     */
    public function __construct(array $pre_processes)
    {
        $this->pre_processes = $pre_processes;
    }

    /**
     * @param     Structure    $structure,
     * @param     Values       $values
     * @param     string       $form_type,
     * @param     Param        $param,
     * @param     Step         $step,
     * @return    mixed[]
     */
    public function process(
        Structure $structure,
        Values $values,
        $form_type,
        Param $param,
        Step $step
    )
    {
        ErrorLogger::setup("pre-process", $param);

        $detect = new MobileDetect();

        foreach ($this->pre_processes as $pre_process) {
            try {
                $new_structure_and_values = $pre_process->process(
                    $structure,
                    $values,
                    $form_type,
                    $param,
                    $step,
                    $detect
                );

                if ($this->validate($new_structure_and_values)) {
                    list($structure, $values) = $new_structure_and_values;
                }
            }
            catch (Exception $e)
            {
                ErrorLogger::error($e);

                throw new PreProcessException($e->getMessage(), $e->errorInfo[1], $e);
            }
        }

        return [$structure, $values];
    }

    /**
     * 渡された値が Structure と Values のペアであることを検証する
     *
     * @param     mixed    $new_structure_and_values
     * @return    bool
     */
    private function validate($new_structure_and_values)
    {
        if (!is_array($new_structure_and_values)) { return false; }

        if (count($new_structure_and_values) != 2) { return false; }

        list($structure, $values) = $new_structure_and_values;

        return $structure instanceof Structure && $values instanceof Values;
    }
}
