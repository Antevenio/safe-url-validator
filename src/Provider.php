<?php
namespace Antevenio\SafeUrl;

use Antevenio\SafeUrl\Provider\Exception;

interface Provider {
    /**
     * @param array $urls
     * @return Threat[]
     * @throws Exception
     */
    public function lookup(array $urls);
    public function getRedirectUrl($url);
}
