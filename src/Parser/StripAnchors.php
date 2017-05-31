<?php

namespace Antevenio\SafeUrl\Parser;

use Antevenio\SafeUrl\Parser;

class StripAnchors implements Parser
{
    public function parse($url)
    {
        return explode('#', $url)[0];
    }
}