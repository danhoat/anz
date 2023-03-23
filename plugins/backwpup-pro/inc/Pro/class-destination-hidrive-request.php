<?php

class BackWPup_Pro_Destination_HiDrive_Request
{
    /**
     * @param string $endpoint
     * @param string $httpMethod
     * @param string $apiMethod
     * @param array $args
     * @return array
     * @throws RuntimeException
     */
    public function request($endpoint, $httpMethod, $apiMethod, $args)
    {
        $url = $endpoint . $apiMethod;

        switch ($httpMethod) {
            case 'POST':
                $response = wp_remote_post($url, $args);
                break;
            case 'PUT':
                $args['method'] = 'PUT';
                $response = wp_remote_request($url, $args);
                break;
            default:
                $response = wp_remote_get($url, $args);
                break;
        }

        if (is_wp_error($response)) {
            throw new RuntimeException(
                $response->get_error_message(),
                $response->get_error_code()
            );
        }

        return [
            'body' => wp_remote_retrieve_body($response),
            'code' => wp_remote_retrieve_response_code($response),
        ];
    }

    /**
     * @param $url
     * @param $headers
     * @param array $fields
     * @return array
     */
    public function curlPostRequest($url, $headers, array $fields = [])
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLINFO_HEADER_OUT, true);

        $result = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        if (curl_errno($ch)) {
            $errorMessage = curl_error($ch);
            curl_close($ch);
            throw new RuntimeException($errorMessage, $httpCode);
        }

        curl_close($ch);

        return [$result, $httpCode];
    }

    /**
     * @param $url
     * @param $headers
     * @return array
     * @throws RuntimeException
     */
    public function curlGetRequest($url, $headers)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLINFO_HEADER_OUT, true);

        $result = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        if (curl_errno($ch)) {
            $errorMessage = curl_error($ch);
            curl_close($ch);
            throw new RuntimeException($errorMessage, $httpCode);
        }

        curl_close($ch);

        return [$result, $httpCode];
    }

    /**
     * @param $url
     * @param $headers
     * @return array
     */
    public function curlDeleteRequest($url, $headers)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLINFO_HEADER_OUT, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");

        $result = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        if (curl_errno($ch)) {
            $errorMessage = curl_error($ch);
            curl_close($ch);
            throw new RuntimeException($errorMessage, $httpCode);
        }

        curl_close($ch);

        return [$result, $httpCode];
    }

    /**
     * @param $url
     * @param $headers
     * @param array $fields
     * @return array
     */
    public function curlPatchRequest($url, $headers, $bodyContent)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PATCH');
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $bodyContent);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLINFO_HEADER_OUT, true);

        $result = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        if (curl_errno($ch)) {
            $errorMessage = curl_error($ch);
            curl_close($ch);
            throw new RuntimeException($errorMessage, $httpCode);
        }

        curl_close($ch);

        return [$result, $httpCode];
    }
}
