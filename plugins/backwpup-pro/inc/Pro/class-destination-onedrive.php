<?php

use Krizalys\Onedrive\Client;
use Krizalys\Onedrive\Onedrive;
use Microsoft\Graph\Graph;

class BackWPup_Pro_Destination_OneDrive extends BackWPup_Destinations
{
    public function option_defaults()
    {
        return [
            'onedriverefreshtoken' => '',
            'onedrivemaxbackups' => 15,
            'onedrivesyncnodelete' => true,
            'onedriveusetrash' => true,
            'onedrivedir' => trailingslashit(sanitize_file_name(get_bloginfo('name'))),
        ];
    }

    public function edit_tab($jobid)
    {
        $clientId = get_site_option(BackWPup_Pro_Settings_APIKeys::OPTION_ONEDRIVE_CLIENT_ID);
        $clientSecret = get_site_option(
            BackWPup_Pro_Settings_APIKeys::OPTION_ONEDRIVE_CLIENT_SECRET
        );

        if (!$clientId || !$clientSecret) {
            BackWPup_Admin::message(
                sprintf(
                    __(
                        'Looks like you haven’t set up any API keys yet. Head over to <a href="%s">Settings | API-Keys</a> and get OneDrive all set up, then come back here.',
                        'backwpup'
                    ),
                    network_admin_url('admin.php') . '?page=backwpupsettings#backwpup-tab-apikey'
                ),
                true
            );
        }

        $clientState = BackWPup_Option::get($jobid, 'onedrive_client_state');
        ?>
        <h3 class="title"><?php esc_html_e('Login', 'backwpup'); ?></h3>
        <p></p>
        <table class="form-table">
            <tr>
                <th scope="row"><?php esc_html_e('Authenticate', 'backwpup'); ?></th>
                <td><?php if (!isset($clientState->token->data->access_token)) { ?>
                        <span class="bwu-message-error"><?php esc_html_e(
                                'Not authenticated!',
                                'backwpup'
                            ); ?></span><br/>
                    <?php } else { ?>
                        <span class="bwu-message-success"><?php esc_html_e(
                                'Authenticated!',
                                'backwpup'
                            ); ?></span><br/>
                    <?php } ?>
                    <a class="button secondary"
                       href="<?php echo admin_url(
                           'admin-ajax.php'
                       ); ?>?action=backwpup_dest_onedrive"><?php esc_html_e(
                            'Reauthenticate',
                            'backwpup'
                        ); ?></a>
                </td>
            </tr>
        </table>

        <h3 class="title"><?php esc_html_e('Backup settings', 'backwpup'); ?></h3>
        <p></p>
        <table class="form-table">
            <tr>
                <th scope="row"><label
                        for="idonedrivedir"><?php esc_html_e(
                            'Folder in OneDrive',
                            'backwpup'
                        ) ?></label></th>
                <td>
                    <input id="idonedrivedir" name="onedrivedir" type="text"
                           value="<?php echo esc_attr(
                               BackWPup_Option::get($jobid, 'onedrivedir')
                           ); ?>"
                           class="regular-text"/>
                </td>
            </tr>
            <tr>
                <th scope="row"><?php esc_html_e('File Deletion', 'backwpup'); ?></th>
                <td>
                    <?php
                    if (BackWPup_Option::get($jobid, 'backuptype') === 'archive') {
                        ?>
                        <label for="idonedrivemaxbackups">
                            <input id="idonedrivemaxbackups" name="onedrivemaxbackups" type="number"
                                   min="0" step="1"
                                   value="<?php echo esc_attr(
                                       BackWPup_Option::get($jobid, 'onedrivemaxbackups')
                                   ); ?>"
                                   class="small-text"/>
                            &nbsp;<?php _e('Number of files to keep in folder.', 'backwpup'); ?>
                        </label>
                        <p><?php _e(
                                '<strong>Warning</strong>: Files belonging to this job are now tracked. Old backup archives which are untracked will not be automatically deleted.',
                                'backwpup'
                            ) ?></p>
                    <?php } else { ?>
                        <label for="idonedrivesyncnodelete">
                            <input class="checkbox" value="1"
                                   type="checkbox" <?php checked(
                                BackWPup_Option::get($jobid, 'onedrivemaxbackups'),
                                true
                            ); ?>
                                   name="onedrivemaxbackups" id="idonedrivesyncnodelete"/>
                            &nbsp;<?php _e(
                                'Do not delete files while syncing to destination!',
                                'backwpup'
                            ); ?>
                        </label>
                    <?php } ?>
                </td>
            </tr>
        </table>
    <?php }

    public function edit_ajax()
    {
        $clientId = get_site_option(BackWPup_Pro_Settings_APIKeys::OPTION_ONEDRIVE_CLIENT_ID);
        $client = Onedrive::client($clientId);

        $httpReferer = $_SERVER['HTTP_REFERER'];
        $parts = parse_url($httpReferer);
        parse_str($parts['query'], $query);
        $jobId = $query['jobid'];

        $url = $client->getLogInUrl(
            [
                'files.read',
                'files.read.all',
                'files.readwrite',
                'files.readwrite.all',
                'offline_access',
            ],
            home_url('wp-load.php'),
            'backwpup_dest_onedrive'
        );

        set_site_transient(
            'backwpup_onedrive_state',
            [
                'state' => $client->getState(),
                'job_url' => $httpReferer,
                'job_id' => $jobId,
            ],
            HOUR_IN_SECONDS
        );

        wp_redirect($url);
        exit;
    }

    public function edit_form_post_save($jobid)
    {
        $data = filter_input_array(
            INPUT_POST,
            [
                'onedrivesyncnodelete' => FILTER_VALIDATE_BOOLEAN,
                'onedriveusetrash' => FILTER_VALIDATE_BOOLEAN,
                'onedrivemaxbackups' => FILTER_SANITIZE_NUMBER_INT,
                'onedrivedir' => FILTER_SANITIZE_URL,
            ]
        );

        BackWPup_Option::update(
            $jobid,
            'onedrivesyncnodelete',
            (bool)$data['onedrivesyncnodelete']
        );
        BackWPup_Option::update($jobid, 'onedriveusetrash', (bool)$data['onedriveusetrash']);
        BackWPup_Option::update(
            $jobid,
            'onedrivemaxbackups',
            abs((int)$data['onedrivemaxbackups'])
        );

        if (!$data['onedrivedir']) {
            return;
        }

        $gdrivedir = wp_normalize_path($data['onedrivedir']);

        if (substr($gdrivedir, 0, 1) !== '/') {
            $gdrivedir = '/' . $data['onedrivedir'];
        }

        BackWPup_Option::update($jobid, 'onedrivedir', $gdrivedir);
    }

    public function job_run_archive(BackWPup_Job $jobObject)
    {

        $jobObject->substeps_todo = 2 + $jobObject->backup_filesize;

        if ($jobObject->substeps_done < $jobObject->backup_filesize) {

            $client = $this->getClient($jobObject->job['jobid']);
            $api = new BackWPup_Pro_Destination_OneDrive_Api(
                $client,
                $this->createGraph($client)
            );

            $name = trailingslashit(
                    ltrim($jobObject->job['onedrivedir'], '/')
                ) . $jobObject->backup_file;
            $uploadUrl = $api->uploadUrl($name);

            $filename = $jobObject->backup_folder . $jobObject->backup_file;
            $size = filesize($filename);

            $resource = fopen($filename, "rb");
            $item = $api->upload($uploadUrl, $resource, $size, $jobObject);
            fclose($resource);

            $jobObject->substeps_done = 1 + $jobObject->backup_filesize;

            $this->file_update_list($jobObject, true, $item);
        }

        $jobObject->substeps_done++;
        return true;
    }

    /**
     * @param $job_settings
     *
     * @return bool
     */
    public function can_run(array $job_settings)
    {
        $onedriveClientState = isset($job_settings['onedrive_client_state'])
        ? $job_settings['onedrive_client_state']
        : null;

        if (!empty($onedriveClientState->token)) {
            return true;
        }

        return false;
    }

    /**
     * @param string $jobdest
     * @return array
     */
    public function file_get_list($jobdest)
    {
        $list = (array)get_site_transient('backwpup_' . strtolower($jobdest));
        $list = array_filter($list);

        return $list;
    }

    public function file_update_list($job, $delete, $item)
    {
        $clientState = BackWPup_Option::get($job->job['jobid'], 'onedrive_client_state');
        $auth = new BackWPup_Pro_Destination_OneDrive_Authorization();
        $client = $auth->getClientFromState($clientState);

        $filecounter = 0;
        $backupfilelist = [];
        $files = [];

        $parentId = $item->getBody()['parentReference']['id'];
        $folder = $client->getDriveItemById($parentId);

        foreach ($folder->getChildren() as $item) {

            if ($this->is_backup_archive($item->name)
                && $this->is_backup_owned_by_job(
                    $item->name,
                    $job->job['jobid']
                ) == true) {

                $backupfilelist[$item->createdDateTime->format('Y-m-d H:i:s')] = $item->id;
            }

            $files[$filecounter]['folder'] = $job->job['onedrivedir'];
            $files[$filecounter]['file'] = $item->id;
            $files[$filecounter]['filename'] = $item->name;
            $files[$filecounter]['downloadurl'] = network_admin_url(
                'admin.php?page=backwpupbackups&action=downloadonedrive&file='
                . $item->id . '&local_file=' . $item->name . '&jobid=' . $job->job['jobid']
            );
            $files[$filecounter]['filesize'] = $item->size;
            $files[$filecounter]['time'] = $item->createdDateTime->format('Y-m-d H:i:s');
            $filecounter++;
        }

        if ($delete && isset($job->job['onedrivemaxbackups']) && $job->job['onedrivemaxbackups'] > 0) {
            if (count($backupfilelist) > $job->job['onedrivemaxbackups']) {

                ksort($backupfilelist);
                $numdeltefiles = 0;

                while ($file = array_shift($backupfilelist)) {

                    if (count($backupfilelist) < $job->job['onedrivemaxbackups']) {
                        break;
                    }

                    // TODO Deprecated Superseded by \Krizalys\Onedrive\Proxy\DriveItemProxy::delete().
                    $client->deleteDriveItem($file);

                    foreach ($files as $key => $filedata) {
                        if ($filedata['file'] == $file) {
                            unset($files[$key]);
                        }
                    }
                    $numdeltefiles++;
                }
                if ($numdeltefiles > 0) {
                    $job->log(
                        sprintf(
                            _n(
                                'One file deleted from Google Drive',
                                '%d files deleted on Google Drive',
                                $numdeltefiles,
                                'backwpup'
                            ),
                            $numdeltefiles
                        ),
                        E_USER_NOTICE
                    );
                }
            }
        }

        set_site_transient('backwpup_' . $job->job['jobid'] . '_onedrive', $files, YEAR_IN_SECONDS);
    }

    public function file_delete($jobdest, $backupfile)
    {
        $files = get_site_transient('backwpup_' . strtolower($jobdest));
        list($jobid, $dest) = explode('_', $jobdest);
        $clientState = BackWPup_Option::get($jobid, 'onedrive_client_state');

        $auth = new BackWPup_Pro_Destination_OneDrive_Authorization();
        $client = $auth->getClientFromState($clientState);

        $client->deleteDriveItem($backupfile);

        foreach ($files as $key => $file) {
            if (is_array($file) && $file['file'] === $backupfile) {
                unset($files[$key]);
            }
        }

        set_site_transient('backwpup_' . strtolower($jobdest), $files, YEAR_IN_SECONDS);
    }

    /**
     * @param $jobid
     * @return \Krizalys\Onedrive\Client
     * @throws Exception
     */
    private function getClient($jobid)
    {
        $auth = new BackWPup_Pro_Destination_OneDrive_Authorization();
        $clientState = BackWPup_Option::get($jobid, 'onedrive_client_state');

        return $auth->getClientFromState($clientState);
    }

    /**
     * @param Client $client
     * @return Graph
     */
    private function createGraph(Client $client)
    {
        $accessToken = $client->getState()->token->data->access_token;
        $graph = new Graph();
        $graph->setApiVersion(BackWPup_Pro_Destination_OneDrive_Api::API_VERSION);
        $graph->setBaseUrl(BackWPup_Pro_Destination_OneDrive_Api::API_URL);
        $graph->setAccessToken($accessToken);

        return $graph;
    }
}
