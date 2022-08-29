<?php

namespace App\Service;

use GuzzleHttp\Client;
use SimpleXMLElement;
use SplFileObject;

class FamilyTreeService
{
    private $client;

    public function getItems(string $url): array
    {
        $items = [];
        $this->client = new Client([
            'headers' => ['User-Agent' => 'QuasiReader']
        ]);
        $itemsResponse = $this->client->request('GET', $url);
        if ($itemsResponse->getStatusCode() == 200) {
            if ($itemsResponse->hasHeader('Content-Length')) {
                $contentLength = $itemsResponse->getHeader('Content-Length')[0];
                echo "<p> Downloaded $contentLength bytes of data. </p>";
            }
            $items = $this->parseItems($itemsResponse->getBody());
        }

        return $items;
    }

    public function parseItems($body): array
    {
        $items = [];
        $xml = new SimpleXMLElement($body->getContents());
        $titles = explode('<dc:title xml:lang="en-US">', $xml->asXML());
        array_shift($titles);
        foreach ($titles as $title) {
            $items[] = explode('</dc:title>', $title)[0];
        }

        return $items;
    }
}