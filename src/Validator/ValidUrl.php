<?php

namespace Antevenio\SafeUrl\Validator;

use Antevenio\SafeUrl\Validator;

class ValidUrl implements Validator
{
    protected function schemeIsHttp($scheme) {
        return $scheme == 'http' || $scheme == 'https';
    }
    public function isValid($url)
    {
        $url = strtolower($url);
        $validUrl = (bool)filter_var($url, FILTER_VALIDATE_URL);
        if ($validUrl) {
            $parsedUrl = parse_url($url);
            if ($parsedUrl) {
                return $this->schemeIsHttp($parsedUrl["scheme"]);
            }
        }
        return false;
    }
}