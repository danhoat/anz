<?php
declare(strict_types=1);

namespace QMS3\Brick\MailSettingsLoader;


interface MailSettingsLoaderInterface
{
    /**
     * @param     string    $filepath
     * @return    array<string,MailSettingOption>
     */
    public function load($filepath);
}
