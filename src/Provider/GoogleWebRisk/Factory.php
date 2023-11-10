<?php
namespace Antevenio\SafeUrl\Provider\GoogleWebRisk;
use Antevenio\SafeUrl\Provider\GoogleWebRisk;
use GuzzleHttp\Client;

class Factory {
    public function create(Client $client, $serverUrl) {
        return new GoogleWebRisk($client, $serverUrl);
    }
}
