<?php

namespace Antevenio\SafeUrl\RedirectionResolver;

interface Cache
{
    public function get($url);
    public function set($url, $resolvedUrl);
}
