<?php

namespace Digima\Http\Requests;

use Digima\Constants;
use Digima\Exceptions\Exception;
use Digima\Http\Responses\AbstractResponse;
use Digima\Http\Responses\JsonResponse;

class StreamContextRequest extends AbstractRequest
{
    /**
     * @throws Exception
     * @return AbstractResponse
     */
    public function send()
    {
        $content = $this->makeContent();
        $streamContext = $this->makeStreamContext($content);
        $resource = fopen($this->getUrl(), 'rb', false, $streamContext);

        if (! is_resource($resource)) {
            throw new Exception('Unable to open stream for request ('.$this->getUrl().')');
        }

        $streamMetaData = stream_get_meta_data($resource);
        $streamWrapperData = $streamMetaData['wrapper_data'];

        $responseBody = stream_get_contents($resource);
        $responseStatusCode = $this->extractResponseStatusCode($streamWrapperData);
        $responseHeaders = $this->extractResponseHeaders($streamWrapperData);
        $responseContentType = $this->extractResponseContentType($responseHeaders);

        return new JsonResponse($this, $responseStatusCode, $responseBody, $responseContentType, $responseHeaders);
    }

    /**
     * @param array $headers
     * @return string
     */
    private function extractResponseContentType(array $headers)
    {
        $contentType = null;

        foreach ($headers as $key => $value) {
            if (strtolower($key) !== 'content-type') {
                continue;
            }

            $parts = explode(';', $value);
            $contentType = trim($parts[0]);
        }

        return $contentType;
    }

    /**
     * @param array $streamWrapperData
     * @return array
     */
    private function extractResponseHeaders(array $streamWrapperData)
    {
        $headers = array();

        foreach ($streamWrapperData as $line) {
            if (strpos($line, ':') === false) {
                continue;
            }

            $parts = explode(':', $line);
            $key = trim($parts[0]);
            $value = trim($parts[1]);
            $headers[$key] = $value;
        }

        return $headers;
    }

    /**
     * @param array $streamWrapperData
     * @return int
     */
    private function extractResponseStatusCode(array $streamWrapperData)
    {
        $httpLine = null;

        foreach ($streamWrapperData as $line) {
            if (strpos($line, 'HTTP') === 0) {
                $httpLine = $line;
                break;
            }
        }

        if ($httpLine === null) {
            return 500;
        }

        $parts = explode(' ', $httpLine);

        return count($parts) >= 2 ? (int) $parts[1] : 500;
    }

    /**
     * @param string $content
     * @return string
     */
    private function makeRequestHeaders($content = null)
    {
        if (! $content) {
            $content = $this->makeContent();
        }

        $this->setHeader('Accept-Language', 'ja');
        $this->setHeader('Content-Length', strlen($content));
        $this->setHeader('Content-Type', $this->getContentType());
        $this->setHeader('Connection', 'Close');

        $requestHeaders = array();

        foreach ($this->getHeaders() as $key => $value) {
            $requestHeaders[] = $key.': '.$value;
        }

        return implode(Constants::LINE_BREAK, $requestHeaders).Constants::LINE_BREAK;
    }

    /**
     * @param string $content
     * @return resource
     */
    private function makeStreamContext($content = null)
    {
        if (! $content) {
            $content = $this->makeContent();
        }

        $apiHostname = parse_url($this->getUrl(), PHP_URL_HOST);

        $headers = $this->makeRequestHeaders($content);

        return stream_context_create(
            array(
                'ssl'  => array(
                    'verify_peer'         => $this->doesVerifyPeers(),
                    'cafile'              => Constants::SSL_CA_BUNDLE_FILE,
                    'CN_match'            => $apiHostname,
                    'disable_compression' => true,
                ),
                'http' => array(
                    'method'        => $this->getHttpMethod(),
                    'header'        => $headers,
                    'content'       => $content,
                    'timeout'       => Constants::API_REQUEST_TIMEOUT,
                    'ignore_errors' => true,
                ),
            )
        );
    }
}
