<?php
declare(strict_types=1);

namespace QMS3\Brick\SubTask;

use Detection\MobileDetect;
use QMS3\Brick\Config\ConfigInterface as Config;
use QMS3\Brick\Param\Param;
use QMS3\Brick\Step\Step;
use QMS3\Brick\Structure\Structure;
use QMS3\Brick\Values\Values;


/**
 * フォームから送信された値を KASIKA に送信してレコード登録するドライバ
 *
 * @since    1.5.2
 */
class Kasika implements SubTaskInterface
{
    /** @var    string */
    private $pid;

    /** @var    array<string,Config> */
    private $options;

    /** @var    bool */
    private $overwrite;

    /** @var    string */
    private $enc;

    /**
     * @param     string                  $pid
     * @param     array<string,Config>    $options
     * @param     bool                    $overwrite
     * @param     string                  $enc
     */
    public function __construct(
        $pid,
        array $options,
        $overwrite,
        $enc
    )
    {
        $this->pid       = $pid;
        $this->options   = $options;
        $this->overwrite = $overwrite;
        $this->enc       = $enc;
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

        $data = array(
            "pid"       => $this->pid,
            "enc"       => $this->enc,
            "overwrite" => $this->overwrite,
        );
        foreach ($this->options as $label => $config) {
            $data[$label] = $config->get_value(
                $structure,
                $values,
                $form_type,
                $param,
                $step,
                $detect
            );
        }

        $endpoint = "https://panda.kasika.io/tracking_conversion/run.php?" . http_build_query($data);

        $handle = curl_init($endpoint);

        $options = array(
            CURLOPT_CUSTOMREQUEST  => "GET",
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CONNECTTIMEOUT => 10,
            CURLOPT_TIMEOUT        => 10,
            CURLOPT_MAXREDIRS      => 10,
        );
        curl_setopt_array($handle, $options);

        $result = curl_exec($handle);
        curl_close($handle);

        $json = json_decode($result, /* $assoc = */ true);

        if (
            $json
            && !empty($json["customer_id"])
            && isset($json["status"])
            && $json["status"] == "success"
        ) {
            $domain = ltrim($_SERVER["HTTP_HOST"], " \n\r\t\v\0.");
            $domain = preg_replace("/^www\./", "", $domain);
            $domain = "." . $domain;

            setcookie(
                /* $name    = */ "_coco_cid",
                /* $value   = */ $json["customer_id"],
                /* $expires = */ time() + 24 * 60 * 60 * 365 * 10,
                /* $path    = */ "/",
                /* $domain  = */ $domain
            );
        }

        return $result;
    }
}
