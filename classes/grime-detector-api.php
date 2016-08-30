<?php

class Grime_Detector_Api {

    const BASE_API = 'http://grimedetector-api.ttarnawski.usermd.net/check';

    /**
     * Class initialization function
     * @static
     * @param string
     * @return bool
     */
    static function check($text) {

        $arg_data = ['text' => $text];
        $data = json_encode( $arg_data );

        $args = [
            'headers' => [
                'Content-Type' => 'application/json'
            ],
            'body' => $data,
            'method' => 'POST'
        ];

        $response = wp_remote_post( self::BASE_API, $args );
        $response_code = wp_remote_retrieve_response_code( $response );
        $response_body = wp_remote_retrieve_body( $response );

        if (!in_array($response_code, array(200,201))){
            return false;
        }

        if (is_wp_error( $response_body )) {
            return false;
        }

        $response_array = json_decode($response_body, 1);
        $status = isset($response_array['status']) ? $response_array['status'] : null;

        return ($status == 'NEGATIVE') ? true : false;
    }
}