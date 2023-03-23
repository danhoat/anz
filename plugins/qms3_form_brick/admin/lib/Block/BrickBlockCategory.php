<?php
declare(strict_types=1);

namespace QMS3\BrickAdmin\Block;


class BrickBlockCategory
{
    /**
     * @return    array[]
     */
    public function category()
    {
        return array(
            'slug' => 'form_brick',
            'title' => 'フォーム',
            'icon' => null,
        );
    }
}
