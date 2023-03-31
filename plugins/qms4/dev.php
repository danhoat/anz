<?php
    /**
     * 日付フォーマット
     *
     * @return    string
     */
    public function date_format()
    {
        if (!function_exists("get_field")) { return "Y年n月j日（J-D）"; }

        $value = get_field("qms3__date_format", $this->qms3_post->ID);
        return empty($value) ? "Y年n月j日（J-D）" : $value;
    }


date_format = get_option( 'date_format' );
        $type_class  = "-{$type}";

        $return = '<time class="c-postTimes__item u-flex--aic ' . esc_attr( $type_class ) . '" datetime="' . esc_attr( wp_date( 'Y-m-d', $timestamp ) ) . '">' .
            Arkhe::get_svg( $type, array( 'class' => 'c-postMetas__icon' ) ) .
            esc_html( wp_date( $date_format, $timestamp ) ) .


            wp_date( 'Y年n月j日（D）', $date->getTimestamp() )

    $tz = wp_timezone();

    $post_date = new \DateTimeImmutable( $this->_wp_post->post_date, $tz );

    $now = new \DateTimeImmutable( 'now', $tz );


    function __post_date(
        ?string $date_format = null
    ): Date
    {
        $date_format = $date_format ?: $this->_param[ 'date_format' ];

        return new Date( $this->_wp_post->post_date, null, $date_format);
    }
