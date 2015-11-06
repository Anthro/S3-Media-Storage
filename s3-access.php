<?php

class S3Access {

    /**
     * Check if we are using constants for the AWS access credentials
     *
     * @return bool
     */
    public static function are_key_constants_set() {
        return defined( 'AWS_ACCESS_KEY_ID' ) && defined( 'AWS_SECRET_ACCESS_KEY' );
    }

    /**
     * Check if access keys are defined either by constants or database
     *
     * @return bool
     */
    public static function are_access_keys_set() {
        return self::get_access_key_id() && self::get_secret_access_key();
    }

    /**
     * Get the AWS key from a constant or the settings
     *
     * @return string
     */
    public static function get_access_key_id() {
        if ( self::are_key_constants_set() ) {
            return AWS_ACCESS_KEY_ID;
        }

        return self::get_setting( 'access_key_id' );
    }

    /**
     * Get the AWS secret from a constant or the settings
     *
     * @return string
     */
    public static function get_secret_access_key() {
        if ( self::are_key_constants_set() ) {
            return AWS_SECRET_ACCESS_KEY;
        }

        return self::get_setting( 'secret_access_key' );
    }  
}
