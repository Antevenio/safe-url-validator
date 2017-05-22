<?php
namespace Antevenio\SafeUrl;

class Validator {
    /**
     * @var Provider
     */
    protected $provider;
    /**
     * @var UrlParser
     */
    protected $urlParser;

    /**
     * Validator constructor.
     * @param Provider $provider
     * @param UrlParser $parser
     */
    public function __construct(Provider $provider, UrlParser $parser) {
        $this->provider = $provider;
        $this->urlParser = $parser;
    }

    public function validateUrls(array $urls) {
        $urls = array_map(array($this->urlParser, "parse"), $urls);
        return ($this->provider->validateUrls($urls));
    }
}