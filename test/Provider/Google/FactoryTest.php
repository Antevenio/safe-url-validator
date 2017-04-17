<?php
namespace Antevenio\SafeUrl\Test\Provider\Google;
use Antevenio\SafeUrl\Provider\Google;
use Antevenio\SafeUrl\Provider\Google\Factory;
use Google_Client;
use PHPUnit\Framework\TestCase;
use PHPUnit_Framework_MockObject_MockObject;

class FactoryTest extends TestCase
{
    /**
     * @var Google_Client | PHPUnit_Framework_MockObject_MockObject
     */
    protected $googleClient;
    /**
     * @var Factory
     */
    protected $sut;

    public function setUp()
    {
        $this->sut = new Factory();
    }

    public function testCreate()
    {
        $this->googleClient = $this->createMock(
            Google_Client::class
        );
        $instance = $this->sut->create($this->googleClient);
        $this->assertInstanceOf(Google::class, $instance);
        $this->assertEquals($this->googleClient,
            $instance->getService()->getClient()
        );
    }
}