<?php
namespace Antevenio\SafeUrl;

interface Parser {
    /**
     * @param string
     * @return string
     */
    public function parse($url);
}
