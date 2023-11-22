<?php

namespace Antevenio\SafeUrl\RedirectionResolver;

use Antevenio\SafeUrl\RedirectionResolver;
use GuzzleHttp\Client;
use GuzzleHttp\TransferStats;

class GuzzleHttp implements RedirectionResolver
{
    /**
     * @var Client
     */
    private $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function resolve($url)
    {
        try {
            $this->client->get(
                $url,
                [
                    'query' => ['get' => 'params'],
                    'on_stats' => function (TransferStats $stats) use (&$url) {
                        $url = $stats->getEffectiveUri()->__toString();
                    }
                ]
            )->getBody()->getContents();
        } catch (\Exception $ex) {
            // TODO: Maybe log something here in the future.
        }

        return $url;
    }
}
