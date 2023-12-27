<?php

namespace Antevenio\SafeUrl\RedirectionResolver\Cache;

use Antevenio\SafeUrl\RedirectionResolver\Cache;

class NoCache implements Cache
{
    public function get($url)
    {
        return false;
    }

    public function set($url, $resolvedUrl)
    {
    }
}
