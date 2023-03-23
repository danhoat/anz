<?php
declare(strict_types=1);

namespace QMS3\Brick\SubTask;

use Detection\MobileDetect;
use QMS3\Brick\Config\ConfigInterface as Config;
use QMS3\Brick\Logger\SubTaskLogger;
use QMS3\Brick\Param\Param;
use QMS3\Brick\Step\Step;
use QMS3\Brick\Structure\Structure;
use QMS3\Brick\Values\Values;


/**
 * フォームから送信された値を kintone に送信してレコード登録するドライバ
 *
 * @since    1.5.0
 */
class Kintone implements SubTaskInterface
{
    /** @var    string */
    private $domain;

    /** @var    string */
    private $api_token;

    /** @var    int */
    private $app_id;

    /** @var    array<string,Config> */
    private $options;

    /**
     * @param    string                  $domain
     * @param    string                  $api_token
     * @param    int                     $app_id
     * @param    array<string,Config>    $options
     */
    public function __construct($domain, $api_token, $app_id, array $options)
    {
        $this->domain    = $domain;
        $this->api_token = $api_token;
        $this->app_id    = $app_id;
        $this->options   = $options;
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

        $endpoint = "https://{$this->domain}.cybozu.com/k/v1/record.json";

        $headers = [
            "X-Cybozu-API-Token: {$this->api_token}",
            "Authorization: Basic {$this->api_token}",
            "Content-Type: application/json",
        ];

        $payload = [
            "app"    => $this->app_id,
            "record" => $this->records(
                $structure,
                $values,
                $form_type,
                $param,
                $step,
                $detect
            ),
        ];


        $handle = curl_init($endpoint);

        curl_setopt_array($handle, [
            CURLOPT_CUSTOMREQUEST  => "POST",
            CURLOPT_HTTPHEADER     => $headers,
            CURLOPT_POSTFIELDS     => json_encode($payload),
            CURLOPT_RETURNTRANSFER => true,
        ]);

        $result = curl_exec($handle);
        $errno = curl_errno($handle);
        $status = curl_getinfo($handle, CURLINFO_RESPONSE_CODE);
        $json = json_decode($result, /* $assoc = */ true);

        if (
            $errno == 0
            && ! is_null( $json )
            && $status == 200
        ) {
            $data = array(
                '$domain' => $this->domain,
                '$app_id' => $this->app_id,
                '$response' => $json,
            );

            SubTaskLogger::channel( 'kintone' )->info( '', $data );
        } else {
            $data = array(
                '$domain' => $this->domain,
                '$api_token' => $this->api_token,
                '$app_id' => $this->app_id,
                '$payload' => $payload,
                '$errno' => $errno,
                '$status' => $status,
                '$json' => $json,
                '$result' => $result,
            );

            $data_str = json_encode($data, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE);

            throw new \RuntimeException("kintone REST API API へのデータ送信に失敗しました。: {$data_str}");
        }

        curl_close($handle);
    }

    /**
     * @param     Structure       $structure
     * @param     Values          $values
     * @param     string          $form_type
     * @param     Param           $param
     * @param     Step            $step
     * @param     MobileDetect    $detect
     * @return    mixed[]
     */
    private function records(
        Structure $structure,
        Values $values,
        $form_type,
        Param $param,
        Step $step,
        MobileDetect $detect
    )
    {
        $default_records = [];
        foreach ($structure as $row) {
            if ($row->type == "title") { continue; }
            if (!isset($values[$row->name])) { continue; }

            $value = $values[$row->name]->value;

            if ($row->type == "checkbox") {
                $default_records[$row->name] = [
                    "value" => array_map(function ($value) {
                        return (string) $value;
                    }, $value),
                ];
            } else if ($row->type == "datepicker") {
                $default_records[$row->name] = [
                    "value" => $value->valid_date
                        ? $value->date->format("Y-m-d")
                        : "",
                ];
            } else {
                $default_records[$row->name] = [
                    "value" => (string) $value,
                ];
            }
        }

        $custom_records = [];
        foreach ($this->options as $label => $config) {
            $custom_records[$label] = [
                "value" => $config->get_value(
                    $structure,
                    $values,
                    $form_type,
                    $param,
                    $step,
                    $detect
                ),
            ];
        }

        return array_merge($default_records, $custom_records);
    }
}
