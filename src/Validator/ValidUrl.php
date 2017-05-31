<?php

namespace Antevenio\SafeUrl\Validator;

use Antevenio\SafeUrl\Validator;

class ValidUrl implements Validator
{
    public function isValid($url)
    {
        return (bool)filter_var($url, FILTER_VALIDATE_URL);
    }
}