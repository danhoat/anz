<?php
declare(strict_types=1);

namespace QMS3\Brick\PreProcess;

use Detection\MobileDetect;
use QMS3\Brick\Exception\SubtaskException;
use QMS3\Brick\Param\Param;
use QMS3\Brick\PreProcess\PreProcessInterface;
use QMS3\Brick\Step\Step;
use QMS3\Brick\Structure\Structure;
use QMS3\Brick\Values\Values;


/**
 * reCAPTCHA のトークンを検証する
 *
 * @since    1.5.2
 */
class RecaptchaVerifier implements PreProcessInterface
{
    /** @var    string */
    private $sitekey;

    /** @var    string */
    private $secret;

    /**
     * @param    string    $sitekey
     * @param    string    $secret
     */
    public function __construct($sitekey, $secret)
    {
        $this->sitekey = $sitekey;
        $this->secret  = $secret;
    }

    /**
     * @param     string          $form_type
     * @param     Param           $param
     * @param     Structure       $structure
     * @param     Step            $step
     * @param     Values          $values
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
        // 入力画面・完了画面ではトークンの検証は不要
        if ($step->is(Step::INPUT) || $step->is(Step::THANKS)) { return true; }

        // トークンが渡されていなければ検証失敗
        if (!isset($_POST["__recaptcha_token"])) { return false; }

        $endpoint = "https://www.google.com/recaptcha/api/siteverify";

        $payload = array_filter([
            "secret"   => $this->secret,
            "response" => $_POST["__recaptcha_token"],
            "remoteip" => $this->remote_ip(),
        ]);

        $handle = curl_init();

        curl_setopt_array($handle, [
            CURLOPT_URL            => $endpoint,
            CURLOPT_CUSTOMREQUEST  => "POST",
            CURLOPT_POSTFIELDS     => http_build_query($payload),
            CURLOPT_RETURNTRANSFER => true,
        ]);

        $response = curl_exec($handle);
        curl_close($handle);

        $result = json_decode($response, /* $assoc = */ true);

        // スコアが 0.5 以上なら検証通過
        // @see https://developers.google.com/recaptcha/docs/v3?hl=en#interpreting_the_score
        if ($result["success"] && $result["score"] >= 0.5) { return true; }

        throw new SubtaskException("reCAPTCHA のトークン検証に失敗しました。: \$response: {$response}");
    }

    /**
     * アクセス元の IP アドレスを取得する
     *
     * @return    string|null
     */
    private function remote_ip()
    {
        // ほとんどの場合において $_SERVER["REMOTE_ADDR"] を見に行けばアクセス元 IP が取れる
        // ただし Web サーバーの前段にロードバランサが置かれているときにはロードバランサの IP が取れてしまう
        // そのため $_SERVER["REMOTE_ADDR"] よりも前に $_SERVER["HTTP_X_FORWARDED_FOR"] を
        // 見にいく
        if (!empty($_SERVER["HTTP_X_FORWARDED_FOR"])) {
            $forwarded_for = $_SERVER["HTTP_X_FORWARDED_FOR"];
            $ips = explode(",", $forwarded_for);
            return $ips[0];
        }

        if (!empty($_SERVER["REMOTE_ADDR"])) { return $_SERVER["REMOTE_ADDR"]; }

        return null;
    }
}
