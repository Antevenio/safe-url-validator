<?php
namespace Antevenio\SafeUrl;

class Client {
    /**
     * @var Provider
     */
    protected $provider;
    /**
     * @var Parser[]
     */
    protected $parsers;
    /**
     * @var Validator[]
     */
    protected $validators;

    /**
     * Validator constructor.
     * @param Provider $provider
     */
    public function __construct(Provider $provider) {
        $this->provider = $provider;
        $this->parsers = [];
        $this->validators = [];
    }

    /**
     * @param Parser $parser
     */
    public function addParser(Parser $parser)
    {
        assert($parser instanceof Parser);
        $this->parsers[] = $parser;
    }

    public function addValidator(Validator $validator)
    {
        assert( $validator instanceof Validator);
        $this->validators[] = $validator;
    }

    /**
     * @param array $urls
     * @return Threat[]
     */
    public function lookup(array $urls) {
        $urls = $this->parse($urls);
        $urls = $this->validate($urls);
        return ($this->provider->lookup($urls));
    }

    protected function parse(array $urls)
    {
        foreach ($this->parsers as $urlParser) {
            $urls = array_map(array($urlParser, "parse"), $urls);
        }
        return $urls;
    }

    protected function validate(array $urls)
    {
        foreach ($this->validators as $validator) {
            $urls = array_values(array_filter($urls, array($validator, "isValid")));
        }
        return $urls;
    }
}