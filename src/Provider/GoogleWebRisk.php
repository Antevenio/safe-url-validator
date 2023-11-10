<?php

namespace Antevenio\SafeUrl\Provider;

use Antevenio\SafeUrl\Provider;
use Antevenio\SafeUrl\Threat;
use GuzzleHttp\Client;

class GoogleWebRisk implements Provider
{
    /**
     * @var Client
     */
    private $httpClient;
    private $serverUrl;
    public function __construct(
        Client $httpClient,
        $serverUrl
    ) {
        $this->httpClient = $httpClient;
        $this->serverUrl = $serverUrl;
    }

    public function lookup(array $urls)
    {
        $threats = [];

        foreach ($urls as $url) {
            $threat = $this->lookupUrl($url);
            if ($threat) {
                $threats[] = $threat;
            }
        }

        return $threats;
    }

    private function lookupUrl($url)
    {
        $checkUrl = $this->serverUrl . "/v1/uris:search";

        try {
            $response = $this->httpClient->post(
                $checkUrl,
                [
                    \GuzzleHttp\RequestOptions::JSON => ['url' => $url]
                ]
            );
        } catch (\Exception $ex) {
            throw new \Exception("webrisk lookup call failed!", 0, $ex);
        }

        $result = $response->getBody()->getContents();
        $result = json_decode($result, true);

        if (!isset($result["threat"]["threatTypes"])) {
            return false;
        }

        return (new Threat())
            ->setUrl($url)
            ->setType($result["threat"]["threatTypes"][0]);
    }

    public function getRedirectUrl($url)
    {
        return $this->serverUrl . "/r?url=" . $url;
    }
}
