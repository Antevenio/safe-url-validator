<?php
namespace Antevenio\SafeUrl\Test;

use Antevenio\SafeUrl\Provider;
use Antevenio\SafeUrl\Threat;
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
     * @var Validator
     */
    protected $sut;

    public function setUp()
    {
        $this->provider = $this->createMock(Provider::class);
        $this->sut = new Validator($this->provider);
    }

    public function testValidateUrls()
    {
        $urls = [ "something" ];
        /** @var Threat[] $ret */
        $ret = [(new Threat())->setUrl("something")];
        $this->provider->expects($this->once())
            ->method("validateUrls")
            ->with($this->equalTo($urls))
            ->will($this->returnValue($ret));
        $this->assertEquals($ret, $this->sut->validateUrls($urls));
    }
}