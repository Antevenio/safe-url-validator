<?php
namespace Antevenio\SafeUrl;

interface UrlParser {
    /**
     * @param string
     * @return string
     */
    public function parse($url);
}
