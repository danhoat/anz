<?php

use Krizalys\Onedrive\Client;
use Microsoft\Graph\Graph;

final class BackWPup_Pro_Destination_OneDrive_Downloader implements BackWPup_Destination_Downloader_Interface
{

    /**
     * @var \BackWpUp_Destination_Downloader_Data
     */
    private $data;

    /**
     * @var resource
     */
    private $local_file_handler;

    /** @var string */
    private $downloadUrl;

    /**
     * @param BackWpUp_Destination_Downloader_Data $data
     */
    public function __construct(BackWpUp_Destination_Downloader_Data $data)
    {

        $this->data = $data;
    }

    /**
     * Clean up things
     */
    public function __destruct()
    {

        fclose($this->local_file_handler);
    }

    /**
     * @inheritdoc
     */
    public function download_chunk($start_byte, $end_byte)
    {

        $this->local_file_handler($start_byte);

        try {
            $client = $this->getClient($this->data->job_id());
            $api = new BackWPup_Pro_Destination_OneDrive_Api(
                $client,
                $this->createGraph($client)
            );

            if ($this->downloadUrl === null) {
                $this->downloadUrl = $api->downloadUrl($this->data->source_file_path());;
            }

            $data = $api->partialRangeDownload($this->downloadUrl, $start_byte, $end_byte);

            $bytes = (int)fwrite($this->local_file_handler, $data);
            if ($bytes === 0) {
                throw new \RuntimeException(__('Could not write data to file.', 'backwpup'));
            }
        } catch (\Exception $e) {
            BackWPup_Admin::message('OneDrive: ' . $e->getMessage());
        }
    }

    /**
     * @inheritdoc
     */
    public function calculate_size()
    {
        $client = $this->getClient($this->data->job_id());
        $item = $client->getDriveItemById($this->data->source_file_path());

        return $item->size;
    }

    /**
     * Set local file hanlder
     *
     * @param int $start_byte
     */
    private function local_file_handler($start_byte)
    {

        if (is_resource($this->local_file_handler)) {
            return;
        }

        // Open file; write mode if $start_byte is 0, else append
        $this->local_file_handler = fopen(
            $this->data->local_file_path(),
            $start_byte == 0 ? 'wb' : 'ab'
        );

        if (!is_resource($this->local_file_handler)) {
            throw new \RuntimeException(__('File could not be opened for writing.', 'backwpup'));
        }
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

