<?php # -*- coding: utf-8 -*-

namespace Inpsyde\BackWPup\Pro\License;

use Inpsyde\BackWPup\Pro\License\Api\LicenseActivation;
use Inpsyde\BackWPup\Pro\License\Api\LicenseDeactivation;
use Inpsyde\BackWPup\Pro\License\Api\LicenseStatusRequest;
use Inpsyde\BackWPup\Settings\SettingTab;

class LicenseSettingsView implements SettingTab
{
    const LICENSE_INSTANCE_KEY = 'license_instance_key';
    const LICENSE_API_KEY = 'license_api_key';
    const LICENSE_PRODUCT_ID = 'license_product_id';
    const LICENSE_STATUS = 'license_status';

    /**
     * @var LicenseActivation
     */
    private $activate;

    /**
     * @var LicenseDeactivation
     */
    private $deactivate;

    /**
     * @var LicenseStatusRequest
     */
    private $status;

    public function __construct(
        LicenseActivation $activate,
        LicenseDeactivation $deactivate,
        LicenseStatusRequest $status
    ) {
        $this->activate = $activate;
        $this->deactivate = $deactivate;
        $this->status = $status;
    }

    public function tab()
    {
        if (!\BackWPup::is_pro()) {
            return;
        }

        $instanceKey = get_site_option(
            self::LICENSE_INSTANCE_KEY
        ) ?: wp_generate_password(12, false);

        $licenseApiKey = get_site_option(self::LICENSE_API_KEY, '');

        $license = new License(
            get_site_option(self::LICENSE_PRODUCT_ID, ''),
            $licenseApiKey,
            $instanceKey,
            get_site_option(self::LICENSE_STATUS, 'inactive')
        );

        $status = $this->status->requestStatusFor($license);

        update_site_option(
            self::LICENSE_STATUS,
            isset($status) ? $status : 'inactive'
        );

        if ($status === 'active') {
            $this->deactivationView($instanceKey, $licenseApiKey);
        } else {
            $this->activationView($instanceKey);
        }
    }

    public function activationView($instanceKey)
    {
        ?>
        <div class="table ui-tabs-hide" id="backwpup-tab-license">
            <table class="form-table">
                <tr>
                    <td><?= wp_kses_post(
                            _x(
                                'This version of BackWPup has a new licensing system that requires a Master Api Key and a Product ID in order to be activated. These values are available in your <a href="https://backwpup.com/my-account" target="_blank">My Account</a> section. Further information is available <a href="https://backwpup.com/docs/backwpup-license-update/" target="_blank">here</a>.',
                                'License',
                                'backwpup'
                            )
                        ) ?></td>
                </tr>
            </table>
            <table class="form-table">
                <tbody>
                <tr>
                    <th scope="row">
                        <label for="license-api-key"><?= esc_html__(
                                'Master API Key',
                                'backwpup'
                            ) ?></label>
                        </label>
                    </th>
                    <td>
                        <input name="<?= self::LICENSE_API_KEY ?>"
                               type="text"
                               id="license-api-key"
                               value="<?= get_site_option(self::LICENSE_API_KEY) ?>"
                               class="regular-text code"/>
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <label for="license-email"><?= esc_html__(
                                'Product ID',
                                'backwpup'
                            ) ?></label>
                    </th>
                    <td>
                        <input name="<?= self::LICENSE_PRODUCT_ID ?>"
                               type="number"
                               id="license-product-id"
                               value="<?= get_site_option(self::LICENSE_PRODUCT_ID) ?>"
                               class="regular-text code"/>
                    </td>
                </tr>
                <input type="hidden" name="license_instance_key" value="<?= $instanceKey ?>"/>
                <input type="hidden" name="license_action" value="activate"/>
                </tbody>
            </table>
            <p class="submit">
                <input type="button" name="license_submit" class="button-primary" value="<?php esc_attr_e(
                    'Activate',
                    'backwpup'
                ); ?>"/>
            </p>
        </div>
        <?php
    }

    public function deactivationView($instanceKey, $licenseApiKey)
    {
        ?>
        <div class="table ui-tabs-hide" id="backwpup-tab-license">
        <table class="form-table">
            <tr>
                <td><?= wp_kses_post(
                        _x(
                            'This version of BackWPup has a new licensing system that requires a Master Api Key and a Product ID in order to be activated. These values are available in your <a href="https://backwpup.com/my-account" target="_blank">My Account</a> section. Further information is available <a href="https://backwpup.com/docs/backwpup-license-update/" target="_blank">here</a>.',
                            'License',
                            'backwpup'
                        )
                    ) ?></td>
            </tr>
        </table>
        <table class="form-table">
            <tbody>
            <tr>
                <td><?=
                    sprintf(
                        _x('License with API Key %s is active.', 'License', 'backwpup'),
                        $this->displayLastDigits($licenseApiKey)
                    ); ?>
                </td>
            </tr>
            <input type="hidden" name="license_instance_key" value="<?= $instanceKey ?>"/>
            <input type="hidden" name="license_action" value="deactivate"/>
            </tbody>
        </table>
            <p class="submit">
                <input type="button" name="license_submit" class="button-primary" value="<?php esc_attr_e(
                    'Deactivate',
                    'backwpup'
                ); ?>"/>
            </p>
        </div>
        <?php
    }

    /**
     * @param $licenseApiKey
     * @return string
     */
    public function displayLastDigits($licenseApiKey)
    {
        return str_pad(
            substr($licenseApiKey, -4),
            strlen($licenseApiKey),
            '*',
            STR_PAD_LEFT
        );
    }
}
