<?php

namespace Digima\Http\Requests;

use Digima\Http\Responses\AbstractResponse;

abstract class AbstractRequest implements RequestInterface
{
    /**
     * @var string
     */
    private $contentType = 'application/json';

    /**
     * @var array
     */
    private $headers = array();

    /**
     * @var string
     */
    private $httpMethod = self::HTTP_GET;

    /**
     * @var array
     */
    private $properties = array();

    /**
     * @var string
     */
    private $url;

    /**
     * @var bool
     */
    private $verifyPeers = true;

    /**
     * @return bool
     */
    public function doesVerifyPeers()
    {
        return $this->verifyPeers;
    }

    /**
     * @return string
     */
    public function getContentType()
    {
        return $this->contentType;
    }

    /**
     * @return array
     */
    public function getHeaders()
    {
        return $this->headers;
    }

    /**
     * @return string
     */
    public function getHttpMethod()
    {
        return $this->httpMethod;
    }

    /**
     * @return array
     */
    public function getProperties()
    {
        return $this->properties;
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @return AbstractResponse
     */
    abstract public function send();

    /**
     * @param string $contentType
     * @return RequestInterface
     */
    public function setContentType($contentType)
    {
        $this->contentType = $contentType;

        return $this;
    }

    /**
     * Set one header.
     *
     * @param string $name
     * @param string $value
     * @return RequestInterface
     */
    public function setHeader($name, $value)
    {
        $this->headers[$name] = $value;

        return $this;
    }

    /**
     * Set all headers, replacing the current ones.
     *
     * @param array $headers
     * @return RequestInterface
     */
    public function setHeaders(array $headers)
    {
        $this->headers = $headers;

        return $this;
    }

    /**
     * Set the HTTP method to one of the following: GET, POST, PUT, DELETE.
     *
     * @param string $httpMethod
     * @return RequestInterface
     */
    public function setHttpMethod($httpMethod)
    {
        $this->httpMethod = $httpMethod;

        return $this;
    }

    /**
     * Set all the properties, replacing the current ones.
     *
     * @param array $properties
     * @return RequestInterface
     */
    public function setProperties(array $properties)
    {
        $this->properties = $properties;

        return $this;
    }

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
    public function setProperty($key, $value)
    {
        $keys = explode('.', $key);
        $this->properties = $this->setPropertyInContainer($keys, $value, $this->properties);

        return $this;
    }

    /**
     * @param string $url
     * @return RequestInterface
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * Set to `true` to verify that the SSL certificate of the request's domain was signed by a trusted entity.
     *
     * @param bool $verify
     * @return RequestInterface
     */
    public function verifyPeers($verify)
    {
        $this->verifyPeers = (bool) $verify;

        return $this;
    }

    /**
     * @return string
     */
    protected function makeContent()
    {
        // Format the content according to the Content-Type.
        // For now, only the Content-Type "application/json" is supported,
        // so we just "json_encode()" the properties.
        return json_encode($this->properties);
    }

    /**
     * @param array $keys
     * @param mixed $value
     * @param array $container
     * @return array
     */
    private function setPropertyInContainer(array $keys, $value, array $container = null)
    {
        if ($container === null) {
            $container = array();
        }

        // If there is no key, we just return the container
        if (count($keys) === 0) {
            return $container;
        }

        $firstKey = array_shift($keys);

        // If there is no more key, we set the value and return the container
        if (count($keys) === 0) {
            $container[$firstKey] = $value;

            return $container;
        }

        $firstKeyContainer = null;

        if (array_key_exists($firstKey, $container)) {
            $firstKeyContainer = $container[$firstKey];
        }

        $container[$firstKey] = $this->setPropertyInContainer($keys, $value, $firstKeyContainer);

        return $container;
    }
}
