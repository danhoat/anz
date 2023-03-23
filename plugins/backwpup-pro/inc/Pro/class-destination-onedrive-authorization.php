<?php

use Krizalys\Onedrive\Client;
use Krizalys\Onedrive\Onedrive;

class BackWPup_Pro_Destination_OneDrive_Authorization
{
    /**
     * It returns a working OneDrive client by renewing its access token.
     * @param stdClass $clientState
     * @return Client
     * @throws Exception
     */
    public function getClientFromState(stdClass $clientState)
    {
        $clientId = get_site_option(BackWPup_Pro_Settings_APIKeys::OPTION_ONEDRIVE_CLIENT_ID);
        $client = Onedrive::client(
            $clientId,
            ['state' => $clientState]
        );

        if ((time() - $clientState->created_on) < $clientState->token->data->expires_in) {
            return $client;
        }

        $clientSecret = BackWPup_Encryption::decrypt(
            get_site_option(
                BackWPup_Pro_Settings_APIKeys::OPTION_ONEDRIVE_CLIENT_SECRET
            )
        );
        $client->renewAccessToken($clientSecret);

        return $client;
    }

    /**
     * It obtains an access token from code and stores it, after that it redirects to current job screen.
     * @throws Exception
     */
    public function getAccessToken()
    {
        $code = filter_input(INPUT_GET, 'code', FILTER_SANITIZE_STRING);
        $state = filter_input(INPUT_GET, 'state', FILTER_SANITIZE_STRING);

        if ($code && $state === 'backwpup_dest_onedrive') {

            $backwpupOnedriveState = get_site_transient('backwpup_onedrive_state');

            $clientId = get_site_option(BackWPup_Pro_Settings_APIKeys::OPTION_ONEDRIVE_CLIENT_ID);
            $client = Onedrive::client(
                $clientId,
                [
                    'state' => $backwpupOnedriveState['state'],
                ]
            );

            $clientSecret = BackWPup_Encryption::decrypt(
                get_site_option(
                    BackWPup_Pro_Settings_APIKeys::OPTION_ONEDRIVE_CLIENT_SECRET
                )
            );

            try {
                $client->obtainAccessToken(
                    $clientSecret,
                    $code
                );
            } catch (Exception $exception) {
                BackWPup_Admin::message($exception->getMessage(), true);
            }

            $clientState = $client->getState();
            $clientState->created_on = time();

            $backwpupOnedriveState['state'] = $client->getState();

            BackWPup_Option::update(
                $backwpupOnedriveState['job_id'],
                'onedrive_client_state',
                $client->getState()
            );

            wp_redirect($backwpupOnedriveState['job_url']);
            exit();
        }
    }
}
