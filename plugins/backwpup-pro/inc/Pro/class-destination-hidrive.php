<?php

class BackWPup_Pro_Destination_HiDrive extends BackWPup_Destinations
{
    const MAX_BACKUPS = 'hidrive_max_backups';
    const SYNC_NO_DELETE = 'hidrive_sync_no_delete';
    const DESTINATION_FOLDER = 'hidrive_destination_folder';

    public function option_defaults()
    {
        return [
            self::MAX_BACKUPS => 15,
            self::SYNC_NO_DELETE => true,
            self::DESTINATION_FOLDER => '/' . trailingslashit(
                    sanitize_file_name(get_bloginfo('name'))
                ),
        ];
    }

    public function edit_tab($jobid)
    {
        $request = new BackWPup_Pro_Destination_HiDrive_Request();
        $authorization = new BackWPup_Pro_Destination_HiDrive_Authorization($request);
        $api = new BackWPup_Pro_Destination_HiDrive_Api($request, $authorization);
        $oauthAuthorizeUrl = $authorization->oauthAuthorizeUrl();
        $hidrivetoken = BackWPup_Option::get($jobid, 'hidrivetoken');

        if (isset($hidrivetoken->access_token)) {
            $userHome = BackWPup_Option::get($jobid, 'hidrive_user_home');
            if (!$userHome) {
                $user = $api->user(($jobid));
                $responseBody = json_decode($user['body']);
                BackWPup_Option::update($jobid, 'hidrive_user_home', $responseBody[0]->home);
            }
        }

        if (!empty($_GET['hidrive_delete_authorization']) && isset($hidrivetoken->access_token)) {
            try {
                $authorization->oauthTokenRevoke($authorization->accessToken($hidrivetoken));
                BackWPup_Option::update($jobid, 'hidrivetoken', []);
            } catch (Exception $exception) {
            }
        }

        BackWPup_Admin::display_messages();

        ?>
        <h3 class="title"><?php esc_html_e('Login', 'backwpup'); ?></h3>
        <p></p>
        <table class="form-table">
            <tr>
                <th scope="row"><?php esc_html_e( 'Authentication', 'backwpup' ); ?></th>
                <td><?php if ( empty( $hidrivetoken->access_token ) ) { ?>
                        <span style="color:red;"><?php esc_html_e( 'Not authenticated!', 'backwpup' ); ?></span>
                    <?php } else { ?>
                        <span style="color:green;"><?php esc_html_e( 'Authenticated!', 'backwpup' ); ?></span>
                        <br />&nbsp;<br />
                        <a class="button secondary"
                           href="<?php echo wp_nonce_url(
                               network_admin_url(
                                   'admin.php?page=backwpupeditjob&hidrive_delete_authorization=1&jobid=' . $jobid . '&tab=dest-hidrive'
                               ),
                               'edit-job'
                           ); ?>"
                           title="<?php esc_html_e(
                               'Delete HiDrive Authentication',
                               'backwpup'
                           ); ?>"><?php esc_html_e( 'Delete HiDrive Authentication', 'backwpup' ); ?></a>
                    <?php } ?>
                </td>
            </tr>
            <tr>
                <th scope="row">
                    <label for="id_sandbox_code"><?php esc_html_e(
                            'App Access to HiDrive',
                            'backwpup'
                        ); ?></label>
                </th>
                <td>
                    <input id="hidrive_authorization_code" name="hidrive_authorization_code"
                           type="text" value=""
                           class="regular-text code"/>&nbsp;
                    <a class="button secondary" href="<?php echo esc_attr(
                        $oauthAuthorizeUrl
                    ); ?>" target="_blank"><?php esc_html_e(
                            'Get HiDrive Authorization Code',
                            'backwpup'
                        ); ?></a>
                </td>
            </tr>
        </table>

        <h3 class="title"><?php esc_html_e('Backup settings', 'backwpup'); ?></h3>
        <p></p>
        <table class="form-table">
            <tr>
                <th scope="row"><label for="destination-folder"><?php esc_html_e(
                            'Destination Folder',
                            'backwpup'
                        ); ?></label></th>
                <td>
                    <input id="destination-folder"
                           name="<?= self::DESTINATION_FOLDER; ?>"
                           type="text"
                           value="<?php echo esc_attr(
                               BackWPup_Option::get($jobid, self::DESTINATION_FOLDER)
                           ); ?>"
                           class="regular-text"/>
                    <p class="description">
                        <?php esc_attr_e(
                            'Specify a subfolder where your backup archives will be stored.',
                            'backwpup'
                        ); ?>
                    </p>
                </td>
            </tr>
            <tr>
                <th scope="row"><?php esc_html_e('File Deletion', 'backwpup'); ?></th>
                <td>
                    <?php
                    if (BackWPup_Option::get($jobid, 'backuptype') === 'archive') {
                        ?>
                        <label for="max-backups">
                            <input id="max-backups"
                                   name="<?= self::MAX_BACKUPS; ?>"
                                   type="number"
                                   min="0" step="1"
                                   value="<?php echo esc_attr(
                                       BackWPup_Option::get($jobid, self::MAX_BACKUPS)
                                   ); ?>"
                                   class="small-text"/>
                            &nbsp;<?php esc_html_e(
                                'Number of files to keep in folder.',
                                'backwpup'
                            ); ?>
                        </label>
                        <p><?php _e(
                                '<strong>Warning</strong>: Files belonging to this job are now tracked. Old backup archives which are untracked will not be automatically deleted.',
                                'backwpup'
                            ) ?></p>
                    <?php } else { ?>
                        <label for="sync-no-delete">
                            <input class="checkbox"
                                   value="1"
                                   type="checkbox"
                                <?php checked(
                                    BackWPup_Option::get($jobid, self::SYNC_NO_DELETE),
                                    true
                                ); ?>
                                   name="dropboxsyncnodelete" id="sync-no-delete"/>
                            &nbsp;<?php esc_html_e(
                                'Do not delete files while syncing to destination!',
                                'backwpup'
                            ); ?>
                        </label>
                    <?php } ?>
                </td>
            </tr>
        </table>
    <?php }

    /**
     * @inheritdoc
     */
    public function file_get_list($jobdest)
    {

        $list = (array)get_site_transient('backwpup_' . strtolower($jobdest));
        $list = array_filter($list);

        return $list;
    }

    /**
     * Update the list of files in the transient.
     *
     * @param BackWPup_Job|int $job Either the job object or job ID
     * @param bool $delete Whether to delete old backups.
     */
    public function file_update_list($job, $delete = false)
    {
        $request = new BackWPup_Pro_Destination_HiDrive_Request();
        $authorization = new BackWPup_Pro_Destination_HiDrive_Authorization($request);
        $api = new BackWPup_Pro_Destination_HiDrive_Api($request, $authorization);
        $userHome = BackWPup_Option::get($job->job['jobid'], 'hidrive_user_home');
        $userHome = str_replace('root/', '', $userHome);

        $backupfilelist = [];
        $filecounter = 0;
        $files = [];

        $fileList = $api->directoryInteraction(
            $job->job['jobid'],
            $userHome . rtrim($job->job['hidrive_destination_folder'], "/")
        );

        $responseBody = json_decode($fileList['body']);

        foreach ($responseBody->members as $data) {

            if ($data->type === 'file'
                && $this->is_backup_owned_by_job($data->name, $job->job['jobid']) === true
            ) {
                if ($this->is_backup_archive($data->name)) {
                    $backupfilelist[$data->mtime] = $data->name;
                }

                $files[$filecounter]['folder'] = dirname($data->path);
                $files[$filecounter]['file'] = $data->path;
                $files[$filecounter]['filename'] = $data->name;
                $files[$filecounter]['downloadurl'] = network_admin_url(
                    'admin.php?page=backwpupbackups&action=downloadhidrive&file=' . $data->path . '&local_file=' . $data->name . '&jobid=' . $job->job['jobid']
                );
                $files[$filecounter]['filesize'] = $data->size;
                $files[$filecounter]['time'] = $data->mtime + (get_option('gmt_offset') * 3600);
                $filecounter++;
            }
        }

        if ( $delete && $job && BackWPup_Option::get( $job->job['jobid'], 'hidrive_max_backups' ) > 0 ) {

            if ( count( $backupfilelist ) > $job->job['hidrive_max_backups'] ) {

                ksort( $backupfilelist );
                $numdeltefiles = 0;

                while ( $file = array_shift( $backupfilelist ) ) {
                    if ( count( $backupfilelist ) < $job->job['hidrive_max_backups'] ) {
                        break;
                    }

                    $api->deleteFile(
                        $job->job['jobid'],
                        $userHome . $job->job['hidrive_destination_folder'] . $file
                    );

                    foreach ( $files as $key => $filedata ) {
                        if ( $filedata['file'] === '/' . $userHome . $job->job['hidrive_destination_folder'] . $file ) {
                            unset( $files[ $key ] );
                        }
                    }

                    $numdeltefiles++;
                }

                if ( $numdeltefiles > 0 ) {
                    $job->log(
                        sprintf(
                            _n(
                                'One file deleted from HiDrive',
                                '%d files deleted on HiDrive',
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

        set_site_transient('backwpup_' . $job->job['jobid'] . '_hidrive', $files, YEAR_IN_SECONDS);
    }

    /**
     * Delete File
     *
     * @param string $jobdest    The destionation for this job.
     * @param string $backupfile The file to delete.
     */
    public function file_delete( $jobdest, $backupfile ) {

        $files = get_site_transient( 'backwpup_' . strtolower( $jobdest ) );
        list( $jobid, $dest ) = explode( '_', $jobdest );

        try {
            $request = new BackWPup_Pro_Destination_HiDrive_Request();
            $authorization = new BackWPup_Pro_Destination_HiDrive_Authorization($request);
            $api = new BackWPup_Pro_Destination_HiDrive_Api($request, $authorization);
            $response = $api->deleteFile($jobid, $backupfile);

            foreach ($files as $key => $file) {
                if (is_array($file) && $file['file'] == $backupfile) {
                    unset($files[$key]);
                }
            }

            unset($response);

        } catch (Exception $e) {
            BackWPup_Admin::message('HIDRIVE: ' . $e->getMessage(), true);
        }

        set_site_transient('backwpup_' . strtolower($jobdest), $files, YEAR_IN_SECONDS);
    }

    /**
     * @param int $jobid
     * @param string $file_path
     * @param string $local_file_path
     */
    public function file_download($jobid, $file_path, $local_file_path = null)
    {
        $request = new BackWPup_Pro_Destination_HiDrive_Request();
        $authorization = new BackWPup_Pro_Destination_HiDrive_Authorization($request);
        $api = new BackWPup_Pro_Destination_HiDrive_Api($request, $authorization);
        $response = $api->download($jobid, $file_path);
        $filename = basename($file_path);

        $handle = fopen($filename, 'a');
        fwrite($handle, $response['body']);
        fclose($handle);

        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($filename));
        readfile($filename);
        exit;
    }

    public function edit_ajax()
    {
    }

    public function edit_form_post_save($jobid)
    {
        $hidriveAuthCode = filter_input(
            INPUT_POST,
            'hidrive_authorization_code',
            FILTER_SANITIZE_STRING
        );
        if (!empty($hidriveAuthCode)) {
            try {
                $request = new BackWPup_Pro_Destination_HiDrive_Request();
                $authorization = new BackWPup_Pro_Destination_HiDrive_Authorization($request);
                $oauthToken = $authorization->oauthToken($hidriveAuthCode);

                $responseBody = json_decode($oauthToken['body']);
                $responseBody->created_on = time();

                BackWPup_Option::update($jobid, 'hidrivetoken', $responseBody);

            } catch (Exception $exception) {
            }
        }

        $destinationFolder = filter_input(
            INPUT_POST,
            self::DESTINATION_FOLDER,
            FILTER_SANITIZE_STRING
        );
        BackWPup_Option::update($jobid, self::DESTINATION_FOLDER, $destinationFolder);

        $maxBackups = filter_input(
            INPUT_POST,
            self::MAX_BACKUPS,
            FILTER_SANITIZE_STRING
        );
        BackWPup_Option::update($jobid, self::MAX_BACKUPS, $maxBackups);

        $syncNoDelete = filter_input(
            INPUT_POST,
            self::SYNC_NO_DELETE,
            FILTER_SANITIZE_STRING
        );
        BackWPup_Option::update($jobid, self::SYNC_NO_DELETE, $syncNoDelete);
    }

    /**
     * @param BackWPup_Job $job_object
     * @return bool
     */
    public function job_run_archive(BackWPup_Job $job_object)
    {
        $job_object->substeps_todo = 2 + $job_object->backup_filesize;
        if ($job_object->steps_data[$job_object->step_working]['SAVE_STEP_TRY'] != $job_object->steps_data[$job_object->step_working]['STEP_TRY']) {
            $job_object->log(
                sprintf(
                    __('%d. Try to send backup file to HiDrive&#160;&hellip;', 'backwpup'),
                    $job_object->steps_data[$job_object->step_working]['STEP_TRY']
                )
            );
        }

        try {
            if ($job_object->substeps_done < $job_object->backup_filesize) {
                $request = new BackWPup_Pro_Destination_HiDrive_Request();
                $authorization = new BackWPup_Pro_Destination_HiDrive_Authorization($request);
                $api = new BackWPup_Pro_Destination_HiDrive_Api($request, $authorization);

                $api->upload($job_object);
            }

            $this->file_update_list($job_object, true);

        } catch(RuntimeException $exception) {

        }

        $job_object->substeps_done++;
        return true;
    }

    public function can_run(array $job_settings)
    {
        if ( empty( $job_settings['hidrivetoken'] ) ) {
            return false;
        }

        return true;
    }
}
