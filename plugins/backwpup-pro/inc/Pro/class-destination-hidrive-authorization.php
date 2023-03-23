<?php

class BackWPup_Pro_Destination_HiDrive_Authorization
{
    const HIDRIVE_AUTH_SERVER = 'https://my.hidrive.com/';
    const OPTION_HIDRIVE_CLIENT_ID = '40fcbd5c84c29e40327c046e6c9e9818';
    const OPTION_HIDRIVE_CLIENT_SECRET = 'bc8d93bef5f986afc580d3c9a0b0386b';

    /**
     * @var BackWPup_Pro_Destination_HiDrive_Request
     */
    private $request;

    public function __construct(BackWPup_Pro_Destination_HiDrive_Request $request)
    {
        $this->request = $request;
    }

    public function oauthAuthorizeUrl()
    {
        return self::HIDRIVE_AUTH_SERVER . sprintf(
                'client/authorize?client_id=%s&response_type=code&scope=user,rw',
                self::OPTION_HIDRIVE_CLIENT_ID
            );
    }

    public function oauthToken($authCode)
    {
        $endpoint = self::HIDRIVE_AUTH_SERVER;

        $hidriveParams = [
            'client_id' => self::OPTION_HIDRIVE_CLIENT_ID,
            'client_secret' => BackWPup_Encryption::decrypt(
                self::OPTION_HIDRIVE_CLIENT_SECRET
            ),
            'grant_type' => 'authorization_code',
            'code' => $authCode,
        ];

        $args['headers'] = [
            'Content-Type' => 'application/x-www-form-urlencoded',
        ];
        $args['body'] = http_build_query($hidriveParams);

        try {
            return $this->request->request(
                $endpoint,
                'POST',
                'oauth2/token',
                $args
            );
        } catch (RuntimeException $exception) {
            $this->adminMessage($exception->getMessage(), true);
        }
    }

    /**
     * It returns a valid access token,
     * if current access token is expired, it will generate a new one from refresh token.
     * @param stdClass $hidriveToken
     * @return string
     */
    public function accessToken(stdClass $hidriveToken)
    {
        if ((time() - $hidriveToken->created_on) < $hidriveToken->expires_in) {
            return $hidriveToken->access_token;
        }

        $generatedToken = $this->oauthTokenFromRefreshToken($hidriveToken->refresh_token);
        $responseBody = json_decode($generatedToken['body']);

        return $responseBody->access_token;
    }

    public function oauthTokenFromRefreshToken($refreshToken)
    {
        $endpoint = self::HIDRIVE_AUTH_SERVER;

        $hidriveParams = [
            'client_id' => self::OPTION_HIDRIVE_CLIENT_ID,
            'client_secret' => BackWPup_Encryption::decrypt(
                self::OPTION_HIDRIVE_CLIENT_SECRET
            ),
            'grant_type' => 'refresh_token',
            'refresh_token' => $refreshToken,
        ];

        $args['headers'] = [
            'Content-Type' => 'application/x-www-form-urlencoded',
        ];
        $args['body'] = http_build_query($hidriveParams);

        try {
            return $this->request->request(
                $endpoint,
                'POST',
                'oauth2/token',
                $args
            );
        } catch (RuntimeException $exception) {
            $this->adminMessage($exception->getMessage(), true);
        }
    }

    public function oauthTokenRevoke($token)
    {
        $endpoint = self::HIDRIVE_AUTH_SERVER;

        $hidriveParams = [
            'client_id' => self::OPTION_HIDRIVE_CLIENT_ID,
            'client_secret' => BackWPup_Encryption::decrypt(
                self::OPTION_HIDRIVE_CLIENT_SECRET
            ),
            'token' => $token,
        ];

        $args['headers'] = [
            'Content-Type' => 'application/x-www-form-urlencoded',
        ];
        $args['body'] = http_build_query($hidriveParams);

        try {
            return $this->request->request(
                $endpoint,
                'POST',
                'oauth2/revoke',
                $args
            );
        } catch (RuntimeException $exception) {
            $this->adminMessage($exception->getMessage(), true);
        }
    }

    /**
     * @param string $message
     * @param bool $error
     */
    protected function adminMessage($message, $error)
    {
        \BackWPup_Admin::message($message, $error);
    }
}
