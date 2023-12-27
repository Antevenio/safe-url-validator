<?php

namespace Antevenio\SafeUrl\Test\RedirectionResolver;

use Antevenio\SafeUrl\RedirectionResolver\Cache;
use Antevenio\SafeUrl\RedirectionResolver\GuzzleHttp;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\Psr7\Request;
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

    public function testResolveShouldNotThrowExceptions()
    {
        $url = 'http://bit.ly/aaaa';

        $mock = new MockHandler([
            new Response(301, ['Location' => 'http://ntvn.io/bbbb']),
            new Response(301, ['Location' => 'http://final.url/']),
            new RequestException('Error Communicating with Server', new Request('GET', 'test'))
        ]);

        $container = [];
        $history = Middleware::history($container);
        $handlerStack = HandlerStack::create($mock);
        $handlerStack->push($history);
        $client = new Client(['handler' => $handlerStack]);
        $sut = new GuzzleHttp($client);
        $this->assertEquals("http://final.url/", $sut->resolve($url));
    }

    public function testResolveShouldReturnPreviouslyCachedUrls()
    {
        $url = 'http://bit.ly/aaaa';
        $resolvedUrl = 'http://final.url/';

        $cache = $this->getMockBuilder(Cache::class)
            ->disableOriginalConstructor()
            ->getMock();
        $client = $this->getMockBuilder(Client::class)
            ->disableOriginalConstructor()
            ->setMethods(["get"])
            ->getMock();
        $sut = (new GuzzleHttp($client))->withCache($cache);

        $cache->expects($this->once())
            ->method("get")
            ->with($this->equalTo($url))
            ->will($this->returnValue($resolvedUrl));

        $client->expects($this->never())
            ->method("get");

        $this->assertEquals($resolvedUrl, $sut->resolve($url));
    }

    public function testResolveShouldCacheUrls()
    {
        $cache = $this->getMockBuilder(Cache::class)
            ->disableOriginalConstructor()
            ->getMock();

        $url = 'http://bit.ly/aaaa';
        $resolvedUrl = 'http://final.url/';

        $mock = new MockHandler([
            new Response(301, ['Location' => 'http://ntvn.io/bbbb']),
            new Response(301, ['Location' => $resolvedUrl]),
            new RequestException('Error Communicating with Server', new Request('GET', 'test'))
        ]);

        $container = [];
        $history = Middleware::history($container);
        $handlerStack = HandlerStack::create($mock);
        $handlerStack->push($history);
        $client = new Client(['handler' => $handlerStack]);
        $sut = (new GuzzleHttp($client))->withCache($cache);
        $cache->expects($this->any())
            ->method("get")
            ->with($this->equalTo($url))
            ->will($this->returnValue(false));
        $cache->expects($this->once())
            ->method("set")
            ->with($this->equalTo($url), $this->equalTo($resolvedUrl));
        $this->assertEquals("http://final.url/", $sut->resolve($url));
    }
}
