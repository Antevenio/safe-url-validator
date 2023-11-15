<?php

namespace Antevenio\SafeUrl\Test\RedirectionResolver;

use Antevenio\SafeUrl\RedirectionResolver\GuzzleHttp;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;

class GuzzleHttpTest extends TestCase
{
    public function testResolve()
    {
        $url = 'http://bit.ly/aaaa';

        $mock = new MockHandler([
            new Response(301, ['Location' => 'http://ntvn.io/bbbb']),
            new Response(301, ['Location' => 'http://final.url/']),
            new Response(200, ['Content-Type' => 'text/html'], 'Mocked Response'),
        ]);

        $container = [];
        $history = Middleware::history($container);
        $handlerStack = HandlerStack::create($mock);
        $handlerStack->push($history);
        $client = new Client(['handler' => $handlerStack]);
        $sut = new GuzzleHttp($client);
        $this->assertEquals("http://final.url/", $sut->resolve($url));
    }
}
