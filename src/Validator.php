<?php
namespace Antevenio\SafeUrl;

interface Validator {
    /**
     * @param string
     * @return boolean
     */
    public function isValid($url);
}