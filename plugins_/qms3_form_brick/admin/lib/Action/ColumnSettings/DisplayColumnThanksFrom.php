<?php
declare(strict_types=1);

namespace QMS3\BrickAdmin\Action\ColumnSettings;


class DisplayColumnThanksFrom
{
    /**
     * @param    string    $column_name
     * @param    int    $post_id
     * @return    void
     */
    public function __invoke( $column_name, $post_id )
    {
        if ( $column_name !== 'qms3_form_thanks_from' ) { return; }

        list( $send_mail, $mail_custom_either_master ) = $this->get_mail( $post_id );
        list( $send_name, $name_custom_either_master ) = $this->get_name( $post_id );

        if ( empty( $send_mail ) ) {
            echo '<i class="qms3_form_config_void">(未設定)</i>';
            return;
        }

        if ( empty( $send_name ) ) {
            echo '
                <p class="qms3_form_send_name__config">
                    <span class="name undefined">名前</span> &lt;<span class="mail ' . $mail_custom_either_master . '">アドレス</span>&gt;
                </p>
                <p class="qms3_form_send_name__value">
                    ' . $send_mail . ' &lt;' . $send_mail . '&gt;
                </p>
            ';
            return;
        }

        echo '
            <p class="qms3_form_send_name__config">
                <span class="name ' . $name_custom_either_master . '">名前</span> &lt;<span class="mail ' . $mail_custom_either_master . '">アドレス</span>&gt;
            </p>
            <p class="qms3_form_send_name__value">
                ' . $send_name . ' &lt;' . $send_mail . '&gt;
            </p>
        ';
    }

    /**
     * @param    int    $post_id
     * @return    string[]
     */
    private function get_mail( $post_id )
    {
        $mail_settings = get_post_meta( $post_id, 'mail_settings', /* $single = */ true );

        if ( ! empty( $mail_settings[ 0 ][ 'thanks' ][ 'from' ] ) ) {
            return array(
                $mail_settings[ 0 ][ 'thanks' ][ 'from' ],
                'custom'
            );
        }

        $mail_setting = get_option( 'brick_master__mail_setting', null );

        if ( ! is_null( $mail_setting[ 'thanks' ][ 'from' ] ) ) {
            return array(
                $mail_setting[ 'thanks' ][ 'from' ],
                'master'
            );
        }

        return array( '', 'master' );
    }

    /**
     * @param    int    $post_id
     * @return    string[]
     */
    private function get_name( $post_id )
    {
        $mail_settings = get_post_meta( $post_id, 'mail_settings', /* $single = */ true );

        if ( ! empty( $mail_settings[ 0 ][ 'thanks' ][ 'from_name' ] ) ) {
            return array(
                $mail_settings[ 0 ][ 'thanks' ][ 'from_name' ],
                'custom'
            );
        }

        $mail_setting = get_option( 'brick_master__mail_setting', null );

        if ( ! is_null( $mail_setting[ 'thanks' ][ 'from_name' ] ) ) {
            return array(
                $mail_setting[ 'thanks' ][ 'from_name' ],
                'master'
            );
        }

        return array( '', 'master' );
    }
}
