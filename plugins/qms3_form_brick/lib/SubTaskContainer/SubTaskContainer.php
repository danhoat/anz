<?php
declare(strict_types=1);

namespace QMS3\Brick\SubTaskContainer;

use Exception;
use Detection\MobileDetect;
use QMS3\Brick\Logger\ErrorLogger;
use QMS3\Brick\Param\Param;
use QMS3\Brick\SubTask\SubTaskInterface as SubTask;
use QMS3\Brick\Step\Step;
use QMS3\Brick\Structure\Structure;
use QMS3\Brick\Values\Values;


/**
 * @since    1.5.0
 */
class SubTaskContainer
{
    /** @var    SubTask[] */
    private $sub_tasks;

    /** @var    Param */
    private $param;

    /**
     * @param    SubTask[]    $sub_tasks
     * @param    Param        $param
     */
    public function __construct(array $sub_tasks, Param $param)
    {
        $this->sub_tasks = $sub_tasks;
        $this->param     = $param;
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
        ErrorLogger::setup("sub-task", $param);

        $detect = new MobileDetect();

        foreach ($this->sub_tasks as $sub_task) {
            try {
                $sub_task->process(
                    $structure,
                    $values,
                    $form_type,
                    $param,
                    $step,
                    $detect
                );
            }
            catch (Exception $e)
            {
                ErrorLogger::error($e);
            }
        }
    }
}
