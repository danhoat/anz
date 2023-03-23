<?php
declare(strict_types=1);

namespace QMS3\BrickAdmin\Action\ColumnSettings;


class DisplayColumnBccNotificationTos
{
    /**
     * @param    string    $column_name
     * @param    int    $post_id
     * @return    void
     */
    public function __invoke( $column_name, $post_id )
    {
        if ( $column_name !== 'qms3_form_bcc_notification_tos' ) { return; }

        list( $tos_str, $custom_either_master ) = $this->get_value( $post_id );
        $mails = $this->normalize( $tos_str );

        if ( empty( $mails ) ) {
            echo '<i class="qms3_form_config_void">(未設定)</i>';
            return;
        }

        $lis = array();
        foreach ( $mails as $mail ) {
            $lis[] = "<li>{$mail}</li>";
        }

        echo '<ul class="' . $custom_either_master . '">' . join( "\n", $lis ) . '</ul>';
    }

    /**
     * @param    int    $post_id
     * @return    string[]
     */
    public function get_value( $post_id )
    {
        $mail_settings = get_post_meta( $post_id, 'mail_settings', /* $single = */ true );

        if ( ! empty( $mail_settings[ 0 ][ 'bccNotification' ][ 'tos_str' ] ) ) {
            return array(
                $mail_settings[ 0 ][ 'bccNotification' ][ 'tos_str' ],
                'custom'
            );
        }

        $mail_setting = get_option( 'brick_master__mail_setting', null );

        if ( ! is_null( $mail_setting[ 'bccNotification' ][ 'tos_str' ] ) ) {
            return array(
                $mail_setting[ 'bccNotification' ][ 'tos_str' ],
                'master'
            );
        }

        return array( '', 'master' );
    }

    /**
     * @param    string    $tos_str
     * @return    string[]
     */
    private function normalize( $tos_str )
    {
        $mails = preg_split( '/[\s,]+/', $tos_str, -1, PREG_SPLIT_NO_EMPTY );
        $mails = array_filter( array_map( 'trim', $mails ) );

        usort( $mails, function( $left, $right ) {
            if ( ! preg_match( '/^([^@]*)@(.*)$/', $left , $l_matches ) ) { return -1; }
            if ( ! preg_match( '/^([^@]*)@(.*)$/', $right, $r_matches ) ) { return 1; }

            $l_head = $l_matches[ 1 ];
            $l_tail = $l_matches[ 2 ];

            $r_head = $r_matches[ 1 ];
            $r_tail = $r_matches[ 2 ];

            switch ( true ) {
                case $l_tail < $r_tail: return -1;
                case $l_tail > $r_tail: return 1;
                case $l_head < $r_head: return -1;
                case $l_head > $r_head: return 1;
                default: return 0;
            }
        } );

        return $mails;
    }
}
