<?php
declare(strict_types=1);

namespace QMS3\Brick;

use QMS3\Brick\Param\Param;
use QMS3\Brick\SubTask\SubTaskInterface as SubTask;
use QMS3\Brick\SubTask\SimpleSubTask;
use QMS3\Brick\SubTaskContainer\SubTaskContainer;


/**
 * @since    1.5.0
 */
class SubTaskInit
{
    /** @var    mixed[] */
    private $sub_tasks;

    /** @var    Param */
    private $param;

    /**
     * @param    mixed[]    $sub_tasks
     * @param    Param      $param
     */
    public function __construct(array $sub_tasks, Param $param)
    {
        $this->sub_tasks = $sub_tasks;
        $this->param     = $param;
    }

    public function init()
    {
        $sub_tasks = [];
        foreach ($this->sub_tasks as $sub_task) {
            if ($sub_task instanceof SubTask) {
                $sub_tasks[] = $sub_task;
            } else if (is_callable($sub_task)) {
                $sub_tasks[] = new SimpleSubTask($sub_task);
            }
        }

        return new SubTaskContainer($sub_tasks, $this->param);
    }
}
