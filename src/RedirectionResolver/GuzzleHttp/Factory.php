<?php

namespace Antevenio\SafeUrl\RedirectionResolver\GuzzleHttp;

use Antevenio\SafeUrl\RedirectionResolver\GuzzleHttp;
use GuzzleHttp\Client;

class Factory
{
    public function create()
    {
        return new GuzzleHttp(new Client());
    }
}
