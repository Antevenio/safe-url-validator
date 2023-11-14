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
     * @var RedirectionResolver
     */
    protected $redirectionResolver;

    /**
     * Validator constructor.
     * @param Provider $provider
     */
    public function __construct(Provider $provider) {
        $this->provider = $provider;
        $this->parsers = [];
        $this->validators = [];
        $this->redirectionResolver = null;
    }

    public function setRedirectionResolver(RedirectionResolver $resolver)
    {
        $this->redirectionResolver = $resolver;
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
        $urls = $this->resolveUrls($urls);
        return ($this->provider->lookup($urls));
    }

    private function resolveUrls(array $urls)
    {
        $resolvedUrls = [];

        foreach ($urls as $url) {
            $resolvedUrls[] = $this->redirectionResolver->resolve($url);
        }

        return $resolvedUrls;
    }

    public function getRedirectUrl($url)
    {
        return $this->provider->getRedirectUrl($url);
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
