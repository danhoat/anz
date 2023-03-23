<?php
declare(strict_types=1);

namespace QMS3\BrickAdmin\Coodinator;

use QMS3\BrickAdmin\Action\ColumnSettings\AddColumns;
use QMS3\BrickAdmin\Action\ColumnSettings\AddRepository;
use QMS3\BrickAdmin\Action\ColumnSettings\DisplayColumnThanksFrom;
use QMS3\BrickAdmin\Action\ColumnSettings\DisplayColumnNotificationTos;
use QMS3\BrickAdmin\Action\ColumnSettings\DisplayColumnBccNotificationTos;
use QMS3\BrickAdmin\Action\ColumnSettings\EnqueueStyle;
use QMS3\BrickAdmin\Action\ColumnSettings\PutColumnSetting;


class ColumnSettingsCoodinator
{
    public function __construct()
    {
        $dirname = 'qms3_form/column_settings';

        /**
         * @see    https://developer.wordpress.org/reference/functions/register_activation_hook/
         */
        $file = plugin_basename( QMS3_FORM_BRICK_PLUGIN_FILE );
        add_action( "activate_{$file}", new PutColumnSetting( $dirname ) );

        add_filter( 'manage_posts_columns', new AddColumns(), 10, 2 );
        add_action( 'manage_posts_custom_column', new DisplayColumnThanksFrom(), 10, 2 );
        add_action( 'manage_posts_custom_column', new DisplayColumnNotificationTos(), 10, 2 );
        add_action( 'manage_posts_custom_column', new DisplayColumnBccNotificationTos(), 10, 2 );
        add_filter( 'acp/storage/repositories', new AddRepository( $dirname ), 10, 2 );

        add_action( 'admin_enqueue_scripts', new EnqueueStyle() );
    }
}
