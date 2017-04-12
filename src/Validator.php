<?php
namespace Antevenio\SafeUrl;

class Validator {
    /**
     * @var Provider
     */
    protected $provider;

    public function __construct(Provider $provider) {
        $this->provider = $provider;
    }

    public function validateUrls(array $urls) {
        return ($this->provider->validateUrls($urls));
    }
    public function checkLinksInHtml() {
        // TODO: Do this.
    }
}