<?php

namespace Digima\Http\Requests;

use Digima\Http\Responses\AbstractResponse;

interface RequestInterface
{
    const HTTP_DELETE = 'DELETE';
    const HTTP_GET = 'GET';
    const HTTP_POST = 'POST';
    const HTTP_PUT = 'PUT';

    /**
     * @return bool
     */
    public function doesVerifyPeers();

    /**
     * @return string
     */
    public function getContentType();

    /**
     * @return array
     */
    public function getHeaders();

    /**
     * @return string
     */
    public function getHttpMethod();

    /**
     * @return array
     */
    public function getProperties();

    /**
     * @return string
     */
    public function getUrl();

    /**
     * @return AbstractResponse
     */
    public function send();

    /**
     * @param string $contentType
     * @return RequestInterface
     */
    public function setContentType($contentType);

    /**
     * Set one header.
     *
     * @param string $name
     * @param string $value
     * @return RequestInterface
     */
    public function setHeader($name, $value);

    /**
     * Set all headers, replacing the current ones.
     *
     * @param array $headers
     * @return RequestInterface
     */
    public function setHeaders(array $headers);

    /**
     * Set the HTTP method to one of the following: GET, POST, PUT, DELETE.
     *
     * @param string $httpMethod
     * @return RequestInterface
     */
    public function setHttpMethod($httpMethod);

    /**
     * Set all the properties, replacing the current ones.
     *
     * @param array $properties
     * @return RequestInterface
     */
    public function setProperties(array $properties);

    /**
     * Set one property.
     * Use a dot notation to create a hierarchy of properties.
     *
     * Example: setProperty('contact.custom_fields.age', 40)
     * Will result in the following array of properties:
     *
     * array(
     *     'contact' => array(
     *         'custom_fields' => array(
     *             'age' => 40,
     *         ),
     *     ),
     * );
     *
     * @param string $key
     * @param mixed $value
     * @return RequestInterface
     */
    public function setProperty($key, $value);

    /**
     * @param string $url
     * @return RequestInterface
     */
    public function setUrl($url);

    /**
     * Set to `true` to verify that the SSL certificate of the request's domain was signed by a trusted entity.
     *
     * @param bool $verify
     * @return RequestInterface
     */
    public function verifyPeers($verify);
}
