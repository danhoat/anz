<?php

class BackWPup_Pro_Destination_HiDrive_Api
{
    const API_URL = 'https://api.hidrive.strato.com/';
    const API_VERSION = '2.1/';

    /**
     * @var BackWPup_Pro_Destination_HiDrive_Request
     */
    private $request;

    /**
     * @var BackWPup_Pro_Destination_HiDrive_Authorization
     */
    private $authorization;

    public function __construct(
        BackWPup_Pro_Destination_HiDrive_Request $request,
        BackWPup_Pro_Destination_HiDrive_Authorization $authorization
    ) {
        $this->request = $request;
        $this->authorization = $authorization;
    }

    /**
     * @param string $token
     * @return array
     */
    public function healthCheck($token)
    {
        $endpoint = self::API_URL . self::API_VERSION;

        $args['headers'] = [
            'Authorization' => 'Bearer ' . $token,
        ];
        $args['body'] = [
            'fields' => 'status',
        ];

        try {
            return $this->request->request(
                $endpoint,
                'GET',
                'app/me',
                $args
            );
        } catch (RuntimeException $exception) {
            $this->adminMessage($exception->getMessage(), true);
        }
    }

    /**
     * @param int $jobId
     * @return array
     */
    public function user($jobId)
    {
        $url = "https://api.hidrive.strato.com/2.1/user?fields=home";
        $hidrivetoken = $this->getBackWPupOption($jobId, 'hidrivetoken');
        $headers[] = 'Authorization: Bearer ' . $this->authorization->accessToken($hidrivetoken);

        try {
            list($result, $httpCode) = $this->request->curlGetRequest($url, $headers);

            return [
                'body' => $result,
                'code' => $httpCode,
            ];

        } catch (RuntimeException $exception) {
            $this->adminMessage($exception->getMessage(), true);
        }
    }

    /**
     * @param BackWPup_Job $jobObject
     * @return array
     */
    public function upload($jobObject)
    {
        if (filesize($jobObject->backup_folder . $jobObject->backup_file) < 5242880) {
            $this->singleUpload($jobObject);
        } else {
            $this->uploadInChunks($jobObject);
        }
    }

    protected function singleUpload($jobObject)
    {
        $userHome = $this->getBackWPupOption($jobObject->job['jobid'], 'hidrive_user_home');
        $userHome = str_replace('root/', '', $userHome);

        $this->createDirectory($jobObject);

        $url = self::API_URL . self::API_VERSION
            . 'file?dir=/'
            . $userHome
            . rtrim($jobObject->job['hidrive_destination_folder'], "/");

        $hidrivetoken = $this->getBackWPupOption($jobObject->job['jobid'], 'hidrivetoken');
        $headers[] = 'Authorization: Bearer ' . $this->authorization->accessToken(
                $hidrivetoken
            );

        $cfile = curl_file_create(
            $jobObject->backup_folder . $jobObject->backup_file,
            \Inpsyde\BackWPupShared\File\MimeTypeExtractor::fromFilePath(
                $jobObject->backup_folder . $jobObject->backup_file
            ),
            $jobObject->job['hidrivedir'] . $jobObject->backup_file
        );

        $fields = [
            'filename' => $cfile,
            'on_exist' => 'autoname',
        ];

        try {
            list($result, $httpCode) = $this->request->curlPostRequest($url, $headers, $fields);

            return [
                'body' => $result,
                'code' => $httpCode,
            ];
        } catch (RuntimeException $exception) {
            $this->adminMessage($exception->getMessage(), true);
        }
    }

    /**
     * @param BackWPup_Job $jobObject
     */
    protected function uploadInChunks(BackWPup_Job $jobObject)
    {
        $this->createDirectory($jobObject);

        $chunkSize = 4194304; // 4MB
        $uploadStart = 0;
        $localFile = $jobObject->backup_folder . $jobObject->backup_file;
        $localFileSize = filesize($localFile);

        $localHandle = fopen($localFile, "rb");
        $contents = fread($localHandle, $chunkSize);

        $targetFile = $jobObject->backup_file;
        $targetHandle = fopen($targetFile, 'w');

        fwrite($targetHandle, $contents);
        fclose($targetHandle);

        $uploadStart += strlen($contents);
        fseek($localHandle, $uploadStart);

        // Free memory
        unset($contents);

        $userHome = $this->getBackWPupOption($jobObject->job['jobid'], 'hidrive_user_home');
        $userHome = str_replace('root/', '', $userHome);

        $url = self::API_URL . self::API_VERSION
            . 'file?dir=/'
            . $userHome
            . rtrim($jobObject->job['hidrive_destination_folder'], "/");

        $hidrivetoken = $this->getBackWPupOption($jobObject->job['jobid'], 'hidrivetoken');
        $headers[] = 'Authorization: Bearer ' . $this->authorization->accessToken(
                $hidrivetoken
            );

        $cfile = curl_file_create(
            $targetFile,
            \Inpsyde\BackWPupShared\File\MimeTypeExtractor::fromFilePath($localFile),
            $targetFile
        );

        $fields = [
            'filename' => $cfile,
            'on_exist' => 'autoname',
        ];

        try {
            $this->request->curlPostRequest($url, $headers, $fields);
        } catch (RuntimeException $exception) {
            $jobObject->log($exception->getMessage() . ' - ' . $exception->getCode());
        }

        unlink($targetFile);

        $headers[] = 'Content-Type: application/octet-stream';

        while ($uploadStart < $localFileSize) {
            $contents = fread($localHandle, $chunkSize * 5); // 20 MB

            $url = self::API_URL . self::API_VERSION . 'file?path=/' . $userHome
                . $jobObject->job['hidrive_destination_folder'] . $targetFile
                . '&offset=' . $uploadStart;

            try {
                $this->request->curlPatchRequest($url, $headers, $contents);
            } catch (RuntimeException $exception) {
                $jobObject->log($exception->getMessage() . ' - ' . $exception->getCode(), E_USER_ERROR);
            }

            $uploadStart += strlen($contents);
            fseek($localHandle, $uploadStart);

            // Free memory
            unset($contents);

            $jobObject->substeps_done = $uploadStart;
            $jobObject->update_working_data();
        }

        fclose($localHandle);
    }

    public function directoryInteraction($jobId, $directory)
    {
        $url = self::API_URL . self::API_VERSION
            . 'dir?path=/'
            . $directory
            . '&fields=chash,ctime,has_dirs,id,members,members.chash,members.ctime,members.has_dirs,members.id,members.image.exif,members.image.height,members.image.width,members.mhash,members.mime_type,members.mohash,members.mtime,members.name,members.nmembers,members.nhash,members.parent_id,members.path,members.readable,members.rshare,members.size,members.type,members.writable,mhash,mohash,mtime,name,nhash,nmembers,parent_id,path,readable,rshare,size,type,writable';

        $hidrivetoken = $this->getBackWPupOption($jobId, 'hidrivetoken');
        $headers[] = 'Authorization: Bearer ' . $this->authorization->accessToken($hidrivetoken);

        try {
            list($result, $httpCode) = $this->request->curlGetRequest($url, $headers);

            return [
                'body' => $result,
                'code' => $httpCode,
            ];
        } catch (RuntimeException $exception) {
            $this->adminMessage($exception->getMessage(), true);
        }
    }

    public function deleteFile($jobId, $path)
    {
        $path = ltrim($path, '/');
        $url = "https://api.hidrive.strato.com/2.1/file?path=/{$path}";
        $hidrivetoken = $this->getBackWPupOption($jobId, 'hidrivetoken');
        $headers[] = 'Authorization: Bearer ' . $this->authorization->accessToken($hidrivetoken);

        try {
            list($result, $httpCode) = $this->request->curlDeleteRequest($url, $headers);

            return [
                'body' => $result,
                'code' => $httpCode,
            ];
        } catch (RuntimeException $exception) {
            $this->adminMessage($exception->getMessage(), true);
        }
    }

    public function download($jobId, $path)
    {
        $url = "https://api.hidrive.strato.com/2.1/file?path={$path}";
        $hidrivetoken = $this->getBackWPupOption($jobId, 'hidrivetoken');
        $headers[] = 'Authorization: Bearer ' . $this->authorization->accessToken($hidrivetoken);

        try {
            list($result, $httpCode) = $this->request->curlGetRequest($url, $headers);

            return [
                'body' => $result,
                'code' => $httpCode,
            ];
        } catch (RuntimeException $exception) {
            $this->adminMessage($exception->getMessage(), true);
        }
    }

    public function temporalDownloadUrl($jobId, $path)
    {
        $url = "https://api.hidrive.strato.com/2.1/file/url?path={$path}";
        $hidrivetoken = $this->getBackWPupOption($jobId, 'hidrivetoken');
        $headers[] = 'Authorization: Bearer ' . $this->authorization->accessToken($hidrivetoken);

        try {
            list($result, $httpCode) = $this->request->curlGetRequest($url, $headers);

            return [
                'body' => $result,
                'code' => $httpCode,
            ];
        } catch (RuntimeException $exception) {
            $this->adminMessage($exception->getMessage(), true);
        }
    }

    /**
     * Create a directory if it does not exist.
     * @param BackWPup_Job $jobObject
     * @return array
     */
    protected function createDirectory($jobObject)
    {
        $userHome = $this->getBackWPupOption($jobObject->job['jobid'], 'hidrive_user_home');
        $userHome = str_replace('root/', '', $userHome);

        $destinationFolder = $jobObject->job['hidrive_destination_folder'];

        $url = self::API_URL . self::API_VERSION
            . 'dir?path=/'
            . $userHome
            . rtrim($destinationFolder, "/");

        $hidrivetoken = $this->getBackWPupOption($jobObject->job['jobid'], 'hidrivetoken');
        $headers[] = 'Authorization: Bearer ' . $this->authorization->accessToken($hidrivetoken);

        try {
            list($result, $httpCode) = $this->request->curlPostRequest($url, $headers);

            return [
                'body' => $result,
                'code' => $httpCode,
            ];
        } catch (RuntimeException $exception) {
            $this->adminMessage($exception->getMessage(), true);
        }
    }

    /**
     * @param string $message
     * @param bool $error
     */
    protected function adminMessage($message, $error) {
        \BackWPup_Admin::message($message, $error);
    }

    /**
     * @param int $jobId
     * @param string $option
     * @return bool|mixed
     */
    protected function getBackWPupOption($jobId, $option)
    {
        return \BackWPup_Option::get($jobId, $option);
    }
}

