<?php

namespace Antevenio\SafeUrl\RedirectionResolver\GuzzleHttp;

use Antevenio\SafeUrl\RedirectionResolver\Cache;
use Antevenio\SafeUrl\RedirectionResolver\GuzzleHttp;
use GuzzleHttp\Client;

class Factory
{
    public function create(Cache $cache = null)
    {
        $client = new GuzzleHttp(new Client());
        if ($cache) {
            $client->withCache($cache);
        }

        return $client;
    }
}
