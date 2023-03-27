<?php
declare(strict_types=1);

namespace QMS3\Brick\Servant;

use QMS3\Brick\Task\TaskInterface as Task;
use QMS3\Brick\Form\Form;


class Servant
{
    /**
     *
     */
    private $tasks;

    /**
     * @param    Task    $task
     * @param    int     $priority
     *
     */
    public function add_task(Task $task, $priority = 10)
    {
        $this->tasks[] = [$task, $priority];
    }

    public function finaly(Task $task)
    {
        $this->tasks[] = [$task, INF];
    }

    public function do_task(Form $form)
    {
        $tasks = $this->tasks;

        usort($tasks, function($left, $right) {
            list($l_task, $l_priority) = $left;
            list($r_task, $r_priority) = $right;

            return $l_priority < $r_priority ? -1 : 1;
        });

        $results = [];
        foreach ($tasks as $task) {
            $results[] = $task->invoke($form);
        }

        return [
            "success" => count(array_filter($results)),
            "failure" => count(array_filter($results, function($result) { return $result; })),
        ];
    }
}
