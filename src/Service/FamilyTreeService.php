<?php

namespace App\Service;

use GuzzleHttp\Client;

class FamilyTreeService
{
    private $client;

    public function getItems(string $url): array
    {
        $items = [];
        $this->client = new Client([
            'headers' => ['Accept' => 'application/xml']
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
        libxml_use_internal_errors(true);
        $doc = new  \DOMDocument();
        $doc->loadHTML($body);
        foreach ($doc->getElementsByTagName("creator") as $node) {
            $items[] = $node->nodeValue;
        }
        return $items;
    }
}