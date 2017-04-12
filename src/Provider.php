<?php
namespace Antevenio\SafeUrl;

interface Provider {
    /**
     * @param array $urls
     * @return Threat[]
     */
    public function validateUrls(array $urls);
}