<?php
declare(strict_types=1);

namespace QMS3\BrickAdmin\PostMeta;


interface PostMetaInterface
{
    /**
     * @return    void
     */
    public function register();

    /**
     * @return    void
     */
    public function enqueue_style();

    /**
     * @return    void
     */
    public function add_meta_box();

    /**
     * @return    void
     */
    public function on_save_post();
}
