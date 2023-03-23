<?php
declare(strict_types=1);

namespace QMS3\BrickAdmin\Coodinator;

use QMS3\BrickAdmin\PostMeta\PostMetaInterface;
use QMS3\BrickAdmin\PostMeta\FormStructure;
use QMS3\BrickAdmin\PostMeta\MailSettings;
use QMS3\BrickAdmin\PostMeta\Memo;
use QMS3\BrickAdmin\PostMeta\NotificationMasterMetaBox;


class PostMetaCoodinator
{
    /** @var    PostMetaInterface[] */
    private $post_metas;

    public function __construct()
    {
        $this->post_metas = array(
            new MailSettings( 'brick' ),
            new FormStructure( 'brick' ),
            new Memo( 'brick' ),

            new NotificationMasterMetaBox( 'brick__notification' ),
            new Memo( 'brick__notification' ),
        );

        $this->register_post_meta();
        add_action( 'admin_menu' , array( $this, 'add_meta_box' ) );
        add_action( 'save_post' , array( $this, 'on_save_post' ) );
        add_action( 'admin_print_styles', array( $this, 'enqueue_style' ) );
    }

    /**
     * @return    void
     */
    private function register_post_meta()
    {
        foreach ( $this->post_metas as $post_meta ) {
            $post_meta->register();
        }
    }

    /**
     * @return    void
     */
    public function add_meta_box()
    {
        foreach ( $this->post_metas as $post_meta ) {
            $post_meta->add_meta_box();
        }
    }

    /**
     * @return    void
     */
    public function on_save_post()
    {
        foreach ( $this->post_metas as $post_meta ) {
            $post_meta->on_save_post();
        }
    }

    /**
     * @return    void
     */
    public function enqueue_style()
    {
        foreach ( $this->post_metas as $post_meta ) {
            $post_meta->enqueue_style();
        }
    }
}
