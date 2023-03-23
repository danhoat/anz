<?php
declare(strict_types=1);

namespace QMS3\Brick\Util;


class StripslashesDeep
{
    /**
     * @param    mixed    $value
     * @return    mixed
     */
    public static function strip( $value )
    {
        return self::stripslashes_deep( $value );
    }

    /**
     * @param    mixed    $value
     * @return    mixed
     */
    private static function stripslashes_deep( $value )
    {
        if ( is_array( $value ) ) {
            foreach ( $value as $index => $item ) {
                $value[ $index ] = self::stripslashes_deep( $item );
            }
            return $value;
        } elseif ( is_object( $value ) ) {
            $object_vars = get_object_vars( $value );
            foreach ( $object_vars as $property_name => $property_value ) {
                $value->$property_name = self::stripslashes_deep( $property_value );
            }
            return $value;
        } else {
            return is_string( $value ) ? stripslashes( $value ) : $value;
        }
    }
}
