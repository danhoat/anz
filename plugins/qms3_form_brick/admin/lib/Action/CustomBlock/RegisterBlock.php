<?php
declare(strict_types=1);

namespace QMS3\BrickAdmin\Action\CustomBlock;

use QMS3\BrickAdmin\Block\Flow;
use QMS3\BrickAdmin\Block\Form;
use QMS3\BrickAdmin\Block\RestrictedArea;


class RegisterBlock
{
    /**
     * @return    void
     */
    public function __invoke()
    {
        ( new Flow() )->register();
        ( new Form() )->register();
        ( new RestrictedArea() )->register();
    }
}
