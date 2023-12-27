<?php

namespace Antevenio\SafeUrl\RedirectionResolver;

use Antevenio\SafeUrl\RedirectionResolver;
use Antevenio\SafeUrl\RedirectionResolver\Cache\NoCache;
use GuzzleHttp\Client;
use GuzzleHttp\TransferStats;

class GuzzleHttp implements RedirectionResolver
{
    /**
     * @var Client
     */
    private $client;
    /**
     * @var Cache
     */
    private $cache;

    public function __construct(
        Client $client
    ) {
        $this->client = $client;
        $this->cache = new NoCache();
    }

    public function withCache(Cache $cache)
    {
        $this->cache = $cache;

        return $this;
    }

    public function resolve($url)
    {
        if ($resolvedUrl = $this->cache->get($url)) {
            return $resolvedUrl;
        }

        try {
            $this->client->get(
                $url,
                [
                    'connect_timeout' => 0.3,
                    'timeout' => 0.3,
                    'on_stats' => function (TransferStats $stats) use (&$resolvedUrl) {
                        $resolvedUrl = $stats->getEffectiveUri()->__toString();
                    }
                ]
            );
        } catch (\Exception $ex) {
            if (!$resolvedUrl) {
                $resolvedUrl = $url;
            }
        }

        $this->cache->set($url, $resolvedUrl);

        return $resolvedUrl;
    }
}
