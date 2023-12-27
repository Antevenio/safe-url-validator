<?php

namespace Antevenio\SafeUrl\RedirectionResolver\Cache;

use Antevenio\SafeUrl\RedirectionResolver\Cache;
use Predis\ClientInterface;

class Redis implements Cache
{
    const KEY_PREFIX = "md_url_resolver:";
    const TTL = 10 * 60;
    /**
     * ClientInterface
     */
    private $redis;

    public function __construct(ClientInterface $redis)
    {
        $this->redis = $redis;
    }

    private function getKeyForUrl($url)
    {
        return self::KEY_PREFIX . $url;
    }

    public function get($url)
    {
        return $this->redis->get($this->getKeyForUrl($url));
    }

    public function set($url, $resolvedUrl)
    {
        $this->redis->set($this->getKeyForUrl($url), $resolvedUrl, self::TTL);
    }
}
