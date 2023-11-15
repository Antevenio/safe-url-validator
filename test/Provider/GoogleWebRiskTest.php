<?php

namespace Antevenio\SafeUrl\Test\Provider;

use Antevenio\SafeUrl\Provider\Exception;
use Antevenio\SafeUrl\Provider\GoogleWebRisk;
use Antevenio\SafeUrl\Threat;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;

class GoogleWebRiskTest extends TestCase
{
    private $serverUrl = 'http://something:1233';
    public function testLookupShouldThrowExceptionOnServerErrors()
    {
        $urls = [
            "https://url1.com",
            "https://url2.com"
        ];

        $mock = new MockHandler([
            new Response(200, ['X-Foo' => 'Bar'], '{"threat":{}}'),
            new RequestException('Error Communicating with Server', new Request('GET', 'test'))
        ]);

        $handlerStack = HandlerStack::create($mock);
        $client = new Client(['handler' => $handlerStack]);
        $sut = new GoogleWebRisk($client, $this->serverUrl);

        $this->expectException(Exception::class);
        $this->expectExceptionMessage("webrisk lookup call failed!");
        $this->expectExceptionCode(0);

        $sut->lookup($urls);
    }

    public function testLookupShouldCallApiAndReturnAppropriateResults()
    {
        $urls = [
            "https://url1.com",
            "https://url2.com",
            "https://url3.com"
        ];

        $mock = new MockHandler([
            new Response(200, ['X-Foo' => 'Bar'], '{"threat":{}}'),
            new Response(200, ['X-Foo' => 'Bar'], '{"threat":{"threatTypes":["SOCIAL_ENGINEERING"]}}'),
            new Response(200, ['X-Foo' => 'Bar'], '{"threat":{"threatTypes":["MALWARE"]}}'),
        ]);

        $handlerStack = HandlerStack::create($mock);
        $client = new Client(['handler' => $handlerStack]);
        $sut = new GoogleWebRisk($client, $this->serverUrl);

        $results = $sut->lookup($urls);

        $this->assertInternalType("array", $results);
        $this->assertCount(2, $results);
        $this->assertEquals(
            (new Threat())
                ->setUrl($urls[1])
                ->setType("SOCIAL_ENGINEERING"),
            $results[0]
        );
        $this->assertEquals(
            (new Threat())
                ->setUrl($urls[2])
                ->setType("MALWARE"),
            $results[1]
        );
    }
}
