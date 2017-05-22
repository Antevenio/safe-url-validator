<?php
namespace Antevenio\SafeUrl\Test\UrlParser;

use Antevenio\SafeUrl\UrlParser;
use PHPUnit\Framework\TestCase;

class NoAnchorsTest extends TestCase {
    /**
     * @var UrlParser
     */
    protected $sut;

    public function setUp()
    {
        $this->sut = new UrlParser\NoAnchors();
    }

    public function parseDataProvider()
    {
        return [
            ["http://www.google.com", "http://www.google.com"],
            ["http://www.google.com/#", "http://www.google.com/"],
            ["http://www.google.com/#anchor1", "http://www.google.com/"]
        ];
    }

    /**
     * @param $input
     * @param $expectedOutput
     * @dataProvider parseDataProvider
     */
    public function testParse($input, $expectedOutput) {
        $this->assertEquals($expectedOutput, $this->sut->parse($input));
    }
}