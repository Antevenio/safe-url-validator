<?php

namespace Antevenio\SafeUrl\RedirectionResolver\GuzzleHttp;

use Antevenio\SafeUrl\RedirectionResolver\GuzzleHttp;
use GuzzleHttp\Client;

class Factory
{
    public function __invoke()
    {
        return new GuzzleHttp(new Client());
    }
}
