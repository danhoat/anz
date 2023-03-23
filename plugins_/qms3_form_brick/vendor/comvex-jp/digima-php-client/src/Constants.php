<?php

namespace Digima;

define('DIGIMA_SSL_CA_BUNDLE_FILE_PATH', __DIR__.'/SSL/ca-bundle.pem');

class Constants
{
    /**
     * Maximum number of seconds allowed for HTTP requests.
     */
    const API_REQUEST_TIMEOUT = 30;

    /**
     * Line break sequence used in requests.
     */
    const LINE_BREAK = "\r\n";

    /**
     * Location of the SSL CA bundle file.
     */
    const SSL_CA_BUNDLE_FILE = DIGIMA_SSL_CA_BUNDLE_FILE_PATH;

    /**
     * Web Form API submit endpoint.
     */
    const WEB_FORM_SUBMIT_ENDPOINT_URL = 'https://form.digima.com/v2/submit';

    /**
     * Web Tracking cookie name.
     */
    const WEB_TRACKING_COOKIE_NAME = 'dgm_bcn';
}
