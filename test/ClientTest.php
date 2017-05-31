<?php

namespace Antevenio\SafeUrl\Test;

use Antevenio\SafeUrl\Provider;
use Antevenio\SafeUrl\Threat;
use Antevenio\SafeUrl\Parser;
use Antevenio\SafeUrl\Client;
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
     * @var PHPUnit_Framework_MockObject_MockObject | Parser
     */
    protected $parser;
    /**
     * @var Client
     */
    protected $sut;

    public function setUp()
    {
        $this->provider = $this->createMock(Provider::class);
        $this->parser = $this->createMock(Parser::class);
        $this->sut = new Client($this->provider, $this->parser);
    }

    public function testLookup()
    {
        $urls = ["url1", "url2"];
        $ret = [(new Threat())->setUrl("something")];
//        $parsedUrls = [ "parsed1", "parsed2" ];
//
//        /** @var Threat[] $ret */
//        $ret = [(new Threat())->setUrl("something")];
//
//        $this->parser->expects($this->any())
//            ->method("parse")
//            ->will($this->returnValueMap(
//                [
//                    [ $urls[0], $parsedUrls[0] ],
//                    [ $urls[1], $parsedUrls[1] ]
//                ]
//            ));
        $this->provider->expects($this->once())
            ->method("lookup")
            ->with($this->equalTo($urls))
            ->will($this->returnValue($ret));
        $this->assertEquals($ret, $this->sut->lookup($urls));
    }

    protected function parseUrl($id, $url)
    {
        return $url . "_parsed_" . $id;
    }

    public function testLookupAppliesParsers()
    {
        $urls = ["url1", "url2"];
        $expectedUrlsToLookup = [
            $this->parseUrl(0, $urls[0]),
            $this->parseUrl(1, $urls[1])
        ];
        $ret = [(new Threat())->setUrl("something")];
        $parsers = [
            [
                "mock" => $this->createMock(Parser::class),
                "values" => [
                    [$urls[0], $this->parseUrl(0, $urls[0])],
                    [$urls[1], $urls[1]]
                ]
            ],
            [
                "mock" => $this->createMock(Parser::class),
                "values" => [
                    [$this->parseUrl(0, $urls[0]), $this->parseUrl(0, $urls[0])],
                    [$urls[1], $this->parseUrl(1, $urls[1])]
                ]
            ]
        ];
        foreach ($parsers as $parser) {
            $parser["mock"]->expects($this->any())
                ->method("parse")
                ->will($this->returnValueMap(
                    $parser["values"]
                ));
            $this->sut->addParser($parser["mock"]);
        }
        $this->provider->expects($this->once())
            ->method("lookup")
            ->with($this->equalTo($expectedUrlsToLookup))
            ->will($this->returnValue($ret));
        $this->assertEquals($ret, $this->sut->lookup($urls));
    }

    public function testLookupRemovesUrlsUsingValidators()
    {
        $urls = ["url1", "url2", "url3"];
        $expectedUrlsToLookup = ["url2"];
        $ret = [(new Threat())->setUrl("something")];
        $validators = [
            [
                "mock" => $this->createMock(Validator::class),
                "values" => [
                    [$urls[0], true],
                    [$urls[1], true],
                    [$urls[2], false]
                ]
            ],
            [
                "mock" => $this->createMock(Validator::class),
                "values" => [
                    [$urls[0], false],
                    [$urls[1], true]
                ]
            ]
        ];
        foreach ($validators as $validator) {
            $validator["mock"]->expects($this->any())
                ->method("isValid")
                ->will($this->returnValueMap(
                    $validator["values"]
                ));
            $this->sut->addValidator($validator["mock"]);
        }
        $this->provider->expects($this->once())
            ->method("lookup")
            ->with($this->equalTo($expectedUrlsToLookup))
            ->will($this->returnValue($ret));
        $this->assertEquals($ret, $this->sut->lookup($urls));
    }
}