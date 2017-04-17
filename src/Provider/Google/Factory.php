<?php
namespace Antevenio\SafeUrl\Provider\Google;
use Antevenio\SafeUrl\Provider\Google;
use Google_Client;
use Google_Service_Safebrowsing;

class Factory {
    public function create(Google_Client $client) {
        return new Google(new Google_Service_Safebrowsing($client));
    }
}
