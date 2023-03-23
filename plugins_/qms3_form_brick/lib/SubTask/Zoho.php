<?php
declare(strict_types=1);

namespace QMS3\Brick\SubTask;

use CurlHandle;
use RuntimeException;
use Detection\MobileDetect;
use QMS3\Brick\Config\ConfigInterface as Config;
use QMS3\Brick\Param\Param;
use QMS3\Brick\Step\Step;
use QMS3\Brick\Structure\Structure;
use QMS3\Brick\Values\Values;


/**
 * フォームから送信された値を ZOHO に送信してレコード登録するドライバ
 *
 * @since    1.5.2
 */
class Zoho implements SubTaskInterface
{
    /** @var    CurlHandle */
    private $curl_handle = null;

    /** @var    string */
    private $client_id;

    /** @var    string */
    private $client_secret;

    /** @var    string */
    private $reflesh_token;

    /** @var    array<string,Config> */
    private $options;

    /**
     * @param    string                  $client_id
     * @param    string                  $client_secret
     * @param    string                  $reflesh_token
     * @param    array<string,Config>    $options
     */
    public function __construct(
        $client_id,
        $client_secret,
        $reflesh_token,
        array $options
    )
    {
        $this->client_id     = $client_id;
        $this->client_secret = $client_secret;
        $this->reflesh_token = $reflesh_token;
        $this->options       = $options;
    }

    /**
     * @param     Structure       $structure
     * @param     Values          $values
     * @param     string          $form_type
     * @param     Param           $param
     * @param     Step            $step
     * @param     MobileDetect    $detect
     * @return    bool
     */
    public function process(
        Structure $structure,
        Values $values,
        $form_type,
        Param $param,
        Step $step,
        MobileDetect $detect
    )
    {
        if (!$step->is('submit')) { return; }

        $endpoint = "https://www.zohoapis.com/crm/v2/functions/leadgen/actions/execute?auth_type=oauth";

        $access_token = $this->fetch_access_token();
        $headers = [
            "Authorization: Zoho-oauthtoken {$access_token}",
        ];

        $payload = [];
        foreach ($this->options as $label => $config) {
            $payload[$label] = $config->get_value(
                $structure,
                $values,
                $form_type,
                $param,
                $step,
                $detect
            );
        }


        $handle = $this->curl_init();

        curl_setopt_array($handle, [
            CURLOPT_URL        => $endpoint,
            CURLOPT_HTTPHEADER => $headers,
            CURLOPT_POSTFIELDS => $payload,
        ]);

        $result = curl_exec($handle);
        $errno = curl_errno($handle);
        $status = curl_getinfo($handle, CURLINFO_RESPONSE_CODE);
        $json = json_decode($result, /* $assoc = */ true);

        if (
            $errno != 0
            || is_null($json)
            || $status != 200
            || !isset($json["code"])
            || $json["code"] != "success"
        ) {
            $data = [
                '$access_token' => $access_token,
                '$payload'      => $payload,
                '$errno'        => $errno,
                '$status'       => $status,
                '$json'         => $json,
                '$result'       => $result,
            ];
            $data_str = json_encode($data, JSON_UNESCAPED_UNICODE);

            throw new RuntimeException("ZOHO API へのデータ送信に失敗しました。: {$data_str}");
        }

        curl_close($this->curl_handle);
        unset($this->curl_handle);

        return $result;
    }

    // ====================================================================== //

    /**
     * @return    CurlHandle
     */
    private function curl_init()
    {
        if (!is_null($this->curl_handle)) { return $this->curl_handle; }

        $handle = curl_init();

        curl_setopt_array($handle, [
            CURLOPT_CUSTOMREQUEST  => "POST",
            CURLOPT_RETURNTRANSFER => true,
        ]);

        $this->curl_handle = $handle;
        return $this->curl_handle;
    }

    private function fetch_access_token()
    {
        $endpoint = "https://accounts.zoho.com/oauth/v2/token";

        $payload = [
            "grant_type"    => "refresh_token",
            "client_id"     => $this->client_id,
            "client_secret" => $this->client_secret,
            "refresh_token" => $this->reflesh_token,
        ];


        $handle = $this->curl_init();

        curl_setopt_array($handle, [
            CURLOPT_URL        => $endpoint,
            CURLOPT_POSTFIELDS => $payload,
        ]);

        $result = curl_exec($handle);
        $errno = curl_errno($handle);
        $status = curl_getinfo($handle, CURLINFO_RESPONSE_CODE);
        $json = json_decode($result, /* $assoc = */ true);

        if ($errno != 0 || !is_array($json) || empty($json["access_token"])) {
            $data = [
                '$payload' => $payload,
                '$errno'   => $errno,
                '$status'  => $status,
                '$json'    => $json,
                '$result'  => $result,
            ];
            $data_str = json_encode($data, JSON_UNESCAPED_UNICODE);

            throw new RuntimeException("アクセストークンの取得に失敗しました。: {$data_str}");
        }

        return $json["access_token"];
    }
}
