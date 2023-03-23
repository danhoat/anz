<?php
declare(strict_types=1);

namespace QMS3\BrickAdmin\Action\ColumnSettings;


class AddColumns
{
    /**
     * @param    array<string,string>    $post_columns
     * @param    string    $post_type
     * @return    array<string,string>
     */
    public function __invoke( array $post_columns, $post_type )
    {
        if ( $post_type !== 'brick' ) { return $post_columns; }

        $pre = array_slice( $post_columns, 0 , 2 );
        $post = array_slice( $post_columns, 2 );

        return array_merge(
            $pre,
            array(
                'qms3_form_thanks_from' => 'サンクスメール送信元',
                'qms3_form_notification_tos' => '先方通知メール送信先',
                'qms3_form_bcc_notification_tos' => '社内通知メール送信先',
            ),
            $post
        );
    }
}
