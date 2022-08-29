<?php

namespace App\Service;

use GuzzleHttp\Client;
use SimpleXMLElement;

class ParseService
{
    private $client;

    public function getRss(string $url): array
    {
        $news = [];
        $this->client = new Client([
            'headers' => ['User-Agent' => 'QuasiReader']
        ]);
        $feed_response = $this->client->request('GET', $url);
        if ($feed_response->getStatusCode() == 200) {
            if ($feed_response->hasHeader('content-length')) {
                $contentLength = $feed_response->getHeader('content-length')[0];
                echo "<p> Downloaded $contentLength bytes of data. </p>";
            }
            $news = $this->parseRss($feed_response->getBody());
        }

        return $news;
    }

    public function parseRss($body): array
    {
        $news = [];
        $xml = new SimpleXMLElement($body);

        foreach($xml->channel->item as $item) {
            $title = $item->title;
            $message = $item->description;
            $news[] = ['title' => $title, 'message' => $message];
        }

        return $news;
    }
}