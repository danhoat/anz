<?php

use Krizalys\Onedrive\Client;
use GuzzleHttp\Psr7\Stream;
use Microsoft\Graph\Graph;

class BackWPup_Pro_Destination_OneDrive_Api
{
    const API_VERSION = 'v1.0';
    const API_URL = 'https://graph.microsoft.com/';

    /**
     * @var Client
     */
    private $client;

    /**
     * @var Graph
     */
    private $graph;

    public function __construct(Client $client, Graph $graph)
    {
        $this->client = $client;
        $this->graph = $graph;
    }

    /**
     * @param $name
     * @return mixed
     * @throws \Microsoft\Graph\Exception\GraphException
     */
    public function uploadUrl($name)
    {
        $name = rawurlencode($name);
        $driveLocator = "/drives/{$this->client->getRoot()->parentReference->driveId}";
        $itemLocator = "/items/{$this->client->getRoot()->id}";
        $endpoint = "$driveLocator$itemLocator:/$name:/createUploadSession";

        $uploadSession = $this->graph
            ->createRequest('POST', $endpoint)
            ->execute();

        return $uploadSession->getBody()['uploadUrl'];
    }

    public function upload($uploadUrl, $resource, $size, $jobObject)
    {
        $chunkSize = 4194304; // 4MB
        $offset = 0;

        while ($data = fread($resource, $chunkSize)) {
            $rangeSize = strlen($data);
            $rangeFirst = $offset;
            $offset += $rangeSize;
            $rangeLast = $offset - 1;

            $headers = [
                'Content-Length' => $rangeSize,
                'Content-Range' => "bytes $rangeFirst-$rangeLast/$size",
            ];

            $response = $this->graph
                ->createRequest('PUT', $uploadUrl)
                ->addHeaders($headers)
                ->attachBody($data)
                ->execute();

            if ($response->getStatus() === 201) {
                return $response;
            }

            $jobObject->steps_data[$jobObject->step_working]['totalread'] += $rangeSize;
            $jobObject->steps_data[$jobObject->step_working]['offset'] += $chunkSize;
            if ($jobObject->job['backuptype'] === 'archive') {
                $jobObject->substeps_done = $jobObject->steps_data[$jobObject->step_working]['offset'];
            }
            $jobObject->update_working_data();
        }
    }

    /**
     * @param $itemId
     * @return mixed
     * @throws \Microsoft\Graph\Exception\GraphException
     */
    public function downloadUrl($itemId)
    {
        $endpoint = "/me/drive/items/{$itemId}";

        $item = $this->graph
            ->createRequest('GET', $endpoint)
            ->execute();

        return $item->getBody()['@microsoft.graph.downloadUrl'];
    }

    public function partialRangeDownload($downloadUrl, $startByte, $endByte)
    {
        $headers = [
            'Range' => "bytes=$startByte-$endByte",
        ];

        return $this->graph
            ->createRequest('GET', $downloadUrl)
            ->addHeaders($headers)
            ->setReturnType(Stream::class)
            ->execute();
    }

    public function download($itemId)
    {
        $driveId = $this->client->getRoot()->parentReference->driveId;
        $endpoint = "/drives/{$driveId}/items/{$itemId}/content";

        return $this->graph
            ->createRequest('GET', $endpoint)
            ->setReturnType(Stream::class)
            ->execute();
    }
}
