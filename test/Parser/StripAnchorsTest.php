<?php
namespace Antevenio\SafeUrl\Test\Parser;
use Antevenio\SafeUrl\Parser\StripAnchors;
use PHPUnit\Framework\TestCase;

class StripAnchorsTest extends TestCase {
    /**
     * @var StripAnchors
     */
    protected $sut;

    public function setUp()
    {
        $this->sut = new StripAnchors();
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