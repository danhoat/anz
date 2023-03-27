<?php
declare(strict_types=1);

namespace QMS3\Brick\SubTask;

use Detection\MobileDetect;
use QMS3\Brick\Param\Param;
use QMS3\Brick\Step\Step;
use QMS3\Brick\Structure\Structure;
use QMS3\Brick\Values\Values;


/**
 * フォームから送信された値を Chatwork にメッセージ送信するドライバ
 *
 * @since    1.5.2
 */
class ChatworkMessage implements SubTaskInterface
{
    /** @var    string */
    private $token;

    /** @var    int */
    private $room_id;

    /** @var    mixed */
    private $message;

    /** @var    int[] */
    private $to_ids;

    /**
     * @param    string    $token
     * @param    int       $room_id
     * @param    mixed     $message
     * @param    int[]     $to_ids
     */
    public function __construct($token, $room_id, $message, array $to_ids = [])
    {
        $this->token   = $token;
        $this->room_id = $room_id;
        $this->message = $message;
        $this->to_ids  = $to_ids;
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

        if (is_callable($this->message)) {
            $message = call_user_func(
                $this->message,
                $values,
                $form_type,
                $param,
                $step,
                $detect
            );
        } else if (is_string($this->message)) {
            $message = $this->render($this->message, $values);
        }

        if (!empty($this->to_ids)) {
            $to_tags = [];
            foreach (array_unique($this->to_ids) as $to_id) {
                $to_tags[] = "[To:{$to_id}]";
            }

            $message = join(" ", $to_tags) . "\n\n" . $message;
        }


        $endpoint = "https://api.chatwork.com/v2/rooms/{$this->room_id}/messages";

        $headers = [
            "X-ChatWorkToken: {$this->token}",
        ];

        $handle = curl_init($endpoint);

        curl_setopt_array($handle, [
            CURLOPT_CUSTOMREQUEST  => "POST",
            CURLOPT_HTTPHEADER     => $headers,
            CURLOPT_POSTFIELDS     => http_build_query([
                "body" => $message,
            ]),
            CURLOPT_RETURNTRANSFER => true,
        ]);

        $result = curl_exec($handle);
        curl_close($handle);

        return $result;
    }

    // ====================================================================== //

    /**
     * @param     string    $template
     * @param     Values    $values
     * @return    string
     */
    private function render($template, $values)
    {
        $variables = [];
        $vals = [];
        foreach ($values as $variable_name => $value) {
            $variables[] = "[{$variable_name}]";
            $vals[] = $value;
        }

        return str_replace($variables, $vals, $template);
    }
}
