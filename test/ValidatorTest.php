<?php
namespace Antevenio\SafeUrl\Test;

use Antevenio\SafeUrl\Provider;
use Antevenio\SafeUrl\Threat;
use Antevenio\SafeUrl\UrlParser;
use Antevenio\SafeUrl\Validator;
use PHPUnit\Framework\TestCase;
use PHPUnit_Framework_MockObject_MockObject;

class GoogleTest extends TestCase
{
    /**
     * @var PHPUnit_Framework_MockObject_MockObject | Provider
     */
    protected $provider;
    /**
     * @var PHPUnit_Framework_MockObject_MockObject | UrlParser
     */
    protected $parser;

    /**
     * @var Validator
     */
    protected $sut;

    public function setUp()
    {
        $this->provider = $this->createMock(Provider::class);
        $this->parser = $this->createMock(UrlParser::class);
        $this->sut = new Validator($this->provider, $this->parser);
    }

    public function testValidateUrls()
    {
        $urls = [ "url1", "url2" ];
        $parsedUrls = [ "parsed1", "parsed2" ];

        /** @var Threat[] $ret */
        $ret = [(new Threat())->setUrl("something")];

        $this->parser->expects($this->any())
            ->method("parse")
            ->will($this->returnValueMap(
                [
                    [ $urls[0], $parsedUrls[0] ],
                    [ $urls[1], $parsedUrls[1] ]
                ]
            ));

        $this->provider->expects($this->once())
            ->method("validateUrls")
            ->with($this->equalTo($parsedUrls))
            ->will($this->returnValue($ret));
        $this->assertEquals($ret, $this->sut->validateUrls($urls));
    }
}