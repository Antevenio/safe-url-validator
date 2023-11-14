<?php

namespace Antevenio\SafeUrl;

interface RedirectionResolver
{
    /**
     * @param $url
     * @return string
     */
    public function resolve($url);
}
