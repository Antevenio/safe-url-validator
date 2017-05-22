<?php

namespace Antevenio\SafeUrl\UrlParser;

use Antevenio\SafeUrl\UrlParser;

class NoAnchors implements UrlParser
{
    public function parse($url)
    {
        return explode('#', $url)[0];
    }
}