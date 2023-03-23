<?php
declare(strict_types=1);

namespace QMS3\BrickAdmin\Coodinator;

use QMS3\BrickAdmin\Action\CustomBlock\AddBlockCategory;
use QMS3\BrickAdmin\Action\CustomBlock\EnqueueFrontScript;
use QMS3\BrickAdmin\Action\CustomBlock\EnqueueScript;
use QMS3\BrickAdmin\Action\CustomBlock\EnqueueStyle;
use QMS3\BrickAdmin\Action\CustomBlock\RegisterBlock;
use QMS3\BrickAdmin\Action\CustomBlock\RegisterPattern;
use QMS3\BrickAdmin\Block\BlockUtil;


class CustomBlockCoodinator
{
    public function __construct()
    {
        add_filter( 'block_categories_all', new AddBlockCategory() );
        add_action( 'init', new RegisterBlock() );
        add_action( 'init', new RegisterPattern() );

        add_action( 'wp_enqueue_scripts', new EnqueueStyle() );
        add_action( 'enqueue_block_editor_assets', new EnqueueStyle() );

        add_action( 'wp_footer', new EnqueueFrontScript() );
        add_action( 'enqueue_block_editor_assets', new EnqueueScript() );

        $block_name = 'brick/flow';
        add_filter( "render_block_{$block_name}", array( $this, 'use__form' ) );

        $block_name = 'brick/form';
        add_filter( "render_block_{$block_name}", array( $this, 'use__form' ) );
    }

    /**
     * @return    string
     */
    public function use__flow( $block_content )
    {
        $block_name = 'brick/flow';
        remove_filter( "render_block_{$block_name}", array( $this, 'use__flow' ) );

        BlockUtil::use( $block_name );

        return $block_content;
    }

    /**
     * @return    string
     */
    public function use__form( $block_content )
    {
        $block_name = 'brick/form';
        remove_filter( "render_block_{$block_name}", array( $this, 'use__form' ) );

        BlockUtil::use( $block_name );

        return $block_content;
    }
}
