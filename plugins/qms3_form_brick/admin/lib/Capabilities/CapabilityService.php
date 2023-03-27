<?php
declare(strict_types=1);

namespace QMS3\BrickAdmin\Capabilities;

use DateTime;
use DateTimeZone;
use RuntimeException;
use QMS3\BrickAdmin\Capabilities\AdministratorCapabilities;
use QMS3\BrickAdmin\Capabilities\AtsumaruCapabilities;
use QMS3\BrickAdmin\Capabilities\AuthorCapabilities;
use QMS3\BrickAdmin\Capabilities\ContributorCapabilities;
use QMS3\BrickAdmin\Capabilities\EditorCapabilities;
use QMS3\BrickAdmin\Capabilities\SubscriberCapabilities;


class CapabilityService
{
    const OPTION_KEY = "qms3_form_capability_setting_completed";

    /**
     * 最後に権限設定された日時を返す
     *
     * @return    DateTime|false    最後に権限設定された日時を返す。権限設定がされていない場合には false を返す
     */
    public function setting_completed()
    {
        $datetime_str = get_option(self::OPTION_KEY);

        if (!$datetime_str) { return false; }

        try {
            $timezone = function_exists("wp_timezone")
                ? wp_timezone()
                : new DateTimeZone("Asia/Tokyo");

            return new DateTime($datetime_str, $timezone);
        }
        catch (RuntimeException $e) {
            return false;
        }
    }

    public function add_caps()
    {
        // 過去に権限設定済みであれば、何もしない
        if ($this->setting_completed()) { return; }

        $caps = new AdministratorCapabilities();
        $caps->set();

        $caps = new AtsumaruCapabilities();
        $caps->set();

        $caps = new AuthorCapabilities();
        $caps->set();

        $caps = new ContributorCapabilities();
        $caps->set();

        $caps = new EditorCapabilities();
        $caps->set();

        $caps = new SubscriberCapabilities();
        $caps->set();


        $timezone = function_exists("wp_timezone")
            ? wp_timezone()
            : new DateTimeZone("Asia/Tokyo");

        $now = new DateTime("now", $timezone);

        update_option(self::OPTION_KEY, $now->format("Y-m-d H:i:s"));
    }

    // ====================================================================== //

    public function reset_caps()
    {
        $caps = new AdministratorCapabilities();
        $caps->set();

        $caps = new AtsumaruCapabilities();
        $caps->set();

        $caps = new AuthorCapabilities();
        $caps->set();

        $caps = new ContributorCapabilities();
        $caps->set();

        $caps = new EditorCapabilities();
        $caps->set();

        $caps = new SubscriberCapabilities();
        $caps->set();


        $timezone = function_exists("wp_timezone")
            ? wp_timezone()
            : new DateTimeZone("Asia/Tokyo");

        $now = new DateTime("now", $timezone);

        update_option(self::OPTION_KEY, $now->format("Y-m-d H:i:s"));
    }
}
