<?php
declare(strict_types=1);

namespace QMS3\Brick\Task;

use QMS3\Brick\Form\Form;


interface TaskInterface
{
    /**
     * @param    Form    $form
     * @param    bool
     */
    public function invoke(Form $form);
}
