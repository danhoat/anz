<?php
declare(strict_types=1);

namespace QMS3\BrickAdmin\Action\CustomBlock;

use QMS3\BrickAdmin\Block\BrickBlockCategory;


class AddBlockCategory
{
    /**
     * @param    array[]    $block_categories
     * @return    array[]
     */
    public function __invoke( array $block_categories )
    {
        $block_categories[] = ( new BrickBlockCategory )->category();

        return  $block_categories;
    }
}
