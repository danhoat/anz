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