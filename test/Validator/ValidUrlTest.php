<?php
namespace Antevenio\SafeUrl\Test\Validator;
use Antevenio\SafeUrl\Validator\ValidUrl;
use PHPUnit\Framework\TestCase;

class ValidUrlTest extends TestCase {
    /**
     * @var ValidUrl
     */
    protected $sut;

    public function setUp()
    {
        $this->sut = new ValidUrl();
    }

    public function urlsDataProvider() {
        return [
            ["http://#", false],
            ["http://www.google.com", true],
            ["https://www.hotmail.com/", true],
            ["https://aaa.net/hey.php", true],
            ["http://ssssss", true],
            ["http://sss#s", true]
        ];
    }

    /**
     * @dataProvider urlsDataProvider
     */
    public function testIsValidCases($input, $returnValue)
    {
        $this->assertEquals($returnValue, $this->sut->isValid($input));
    }
}