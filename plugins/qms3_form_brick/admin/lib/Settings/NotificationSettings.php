<?php
declare(strict_types=1);

namespace QMS3\BrickAdmin\Settings;

use QMS3\BrickAdmin\Exception\UnknownKeyException;


/**
 * @property    array<int,array>    $mail_settings
 */
class NotificationSettings
{
    /** @var    int */
    private $post_id;

    /** @var    array[] */
    private $mail_settings;

    /**
     * @param    int        $post_id
     * @param    array[]    $mail_settings
     */
    private function __construct( $post_id, $mail_settings )
    {
        $this->post_id       = $post_id;
        $this->mail_settings = $mail_settings;
    }

    /**
     * @param     string    $name
     * @return    mixed
     */
    public function __get($name)
    {
        $method_name = "_get__{$name}";

        if (!method_exists($this, $method_name)) {
            throw new UnknownKeyException($name);
        }

        $method_ref = new \ReflectionMethod($this, $method_name);
        if (!$method_ref->isProtected()) {
            throw new UnknownKeyException($name);
        }

        return call_user_func([$this, $method_name]);
    }

    /**
     * @param     string    $name
     * @param     mixed     $value
     * @return    void
     */
    public function __set($name, $value)
    {
        $method_name = "_set__{$name}";

        if (!method_exists($this, $method_name)) {
            throw new UnknownKeyException($name);
        }

        $method_ref = new \ReflectionMethod($this, $method_name);
        if (!$method_ref->isProtected()) {
            throw new UnknownKeyException($name);
        }

        call_user_func([$this, $method_name], $value);
    }

    // ====================================================================== //

    /**
     * @return    array[]
     */
    protected function _get__mail_settings()
    {
        return $this->mail_settings;
    }

    /**
     * @param     mixed    $value
     * @return    void
     */
    protected function _set__mail_settings( $value )
    {
        if ( is_null( $value )) { $value = self::default_mail_settings(); }

        $this->mail_settings = $this->sanitize( $value );
    }

    // ====================================================================== //

    /**
     * @param    int    $post_id
     * @return    self
     */
    public static function from_post_id( $post_id )
    {
        $mail_settings = get_post_meta( $post_id, 'mail_settings', /* $single = */ true );

        if ( empty( $mail_settings ) ) { $mail_settings = self::default_mail_settings(); }

        return new self( $post_id, $mail_settings );
    }

    /**
     * @param    string    $slug
     * @return    self
     */
    public static function from_slug( $slug )
    {
        $query = new \WP_Query( array(
            'post_type' => 'brick__notification',
            'post_status' => 'publish',
            'name' => trim( $slug ),
            'fields' => 'ids',
        ) );

        if ( ! $query->found_posts ) {
            throw new \RuntimeException( "フォーム設定が見つかりません: \$slug: $slug" );
        }

        $post_id = $query->posts[ 0 ];
        $mail_settings = get_post_meta( $post_id, 'mail_settings', /* $single = */ true );

        if ( empty( $mail_settings ) ) { $mail_settings = self::default_mail_settings(); }

        return new self( $post_id, $mail_settings );
    }

    /**
     * @return    array<string,bool>
     */
    public function save()
    {
        $results = [];

        $results[ 'mail_settings' ] = update_post_meta(
            $this->post_id,
            'mail_settings',
            $this->mail_settings
        );

        return $results;
    }

    // ====================================================================== //

    /**
     * @return    array<string,array>
     */
    public static function default_mail_settings()
    {
        return array(
            'thanks' => array(
                'from' => '',
                'from_name' => '',
                'subject_template' => '',
                'main_text_template' => '',
                'after_text_template' => '',
                'signature_template' => '',
            ),
            'notification' => array(
                'to' => '',
                'from' => '',
                'from_name' => '',
                'subject_template' => '',
            ),
        );
    }

    /**
     * @param     array[]    $mail_settings
     * @return    array[]
     */
    private function sanitize( $mail_settings )
    {
        $sanitized = array();

        $sanitized[ 'thanks' ] = array_merge(
            $mail_settings[ 'thanks' ],
            array(
                'from' => trim( $mail_settings[ 'thanks' ][ 'from' ] ),
                'from_name' => trim( $mail_settings[ 'thanks' ][ 'from_name' ] ),
                'subject_template' => trim( $mail_settings[ 'thanks' ][ 'subject_template' ] ),
            )
        );

        $sanitized[ 'notification' ] = array(
            'to' => $this->sanitize_email( $mail_settings[ 'notification' ][ 'to' ] ),
            'from' => trim( $mail_settings[ 'notification' ][ 'from' ] ),
            'from_name' => trim( $mail_settings[ 'notification' ][ 'from_name' ] ),
            'subject_template' => trim( $mail_settings[ 'notification' ][ 'subject_template' ] ),
        );

        return $sanitized;
    }

    /**
     * @param     string    $emails_str
     * @return    string
     */
    private function sanitize_email($emails_str)
    {
        $email_strs = preg_split( '/[,\n]/', $emails_str, -1, PREG_SPLIT_NO_EMPTY );

        $emails = array();
        foreach ( $email_strs as $email ) {
            $email = trim( $email, " \n\r\t\v\0　" );

            if ( $email ) { $emails[] = $email; }
        }

        return join( "\n", $emails );
    }
}
