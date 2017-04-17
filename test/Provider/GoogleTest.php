<?php
namespace Antevenio\SafeUrl\Test\Provider;
use Antevenio\SafeUrl\Provider\Google;
use Antevenio\SafeUrl\Threat;
use Google_Service_Safebrowsing;
use Google_Service_Safebrowsing_FindThreatMatchesRequest;
use Google_Service_Safebrowsing_FindThreatMatchesResponse;
use Google_Service_Safebrowsing_Resource_ThreatMatches;
use Google_Service_Safebrowsing_ThreatMatch;
use PHPUnit\Framework\TestCase;
use PHPUnit_Framework_MockObject_MockObject;

class GoogleTest extends TestCase
{
    /**
     * @var PHPUnit_Framework_MockObject_MockObject | Google_Service_Safebrowsing
     */
    protected $gsSafebrowsing;
    /**
     * @var PHPUnit_Framework_MockObject_MockObject |
     * Google_Service_Safebrowsing_Resource_ThreatMatches
     */
    protected $gsSbThreatMatches;
    /**
     * @var Google
     */
    protected $sut;

    public function setUp()
    {
        $this->gsSafebrowsing = $this->createMock(
            Google_Service_Safebrowsing::class
        );
        $this->gsSbThreatMatches = $this->createMock(
            Google_Service_Safebrowsing_Resource_ThreatMatches::class
        );
        $this->gsSafebrowsing->threatMatches = $this->gsSbThreatMatches;
        $this->sut = new Google($this->gsSafebrowsing);
    }

    public function testValidateUrls()
    {
        $urls = [
            "http://url1.url.url",
            "http://url2.url.url"
        ];

        $expectedRequest = new Google_Service_Safebrowsing_FindThreatMatchesRequest(
            [
                "threatInfo" => [
                    "threatTypes"=> [
                        "MALWARE",
                        "SOCIAL_ENGINEERING",
                        "UNWANTED_SOFTWARE",
                        "POTENTIALLY_HARMFUL_APPLICATION"
                    ],
                    "platformTypes" => [
                        "ANY_PLATFORM"
                    ],
                    "threatEntryTypes" => [
                        "URL"
                    ],
                    "threatEntries" => [
                        [
                            "url" => $urls[0]
                        ],
                        [
                            "url" => $urls[1]
                        ]
                    ]
                ]
            ]
        );

        $findThreatMatchesResponse = new Google_Service_Safebrowsing_FindThreatMatchesResponse();

        $expectedResult = [];
        $threatMatches = [];
        for ($i = 0; $i < count($urls); $i++) {
            $expectedResult[] = (new Threat())
                ->setUrl($urls[$i])
                ->setType("type".$i)
                ->setPlatform("plaform".$i);
            $threatMatches[] = new Google_Service_Safebrowsing_ThreatMatch([
                "threatType" => $expectedResult[$i]->getType(),
                "platformType" => $expectedResult[$i]->getPlatform(),
                "threat" => new \Google_Service_Safebrowsing_ThreatEntry([
                    "url" => $expectedResult[$i]->getUrl()
                ])
            ]);
        }

        $findThreatMatchesResponse->setMatches($threatMatches);

        $this->gsSbThreatMatches->expects($this->once())
            ->method("find")
            ->with($this->equalTo($expectedRequest))
            ->will($this->returnValue($findThreatMatchesResponse));

        $ret = $this->sut->validateUrls($urls);
        $this->assertInternalType("array", $ret);
        $this->assertCount(2, $ret);
        $this->assertEquals( $expectedResult, $ret );
    }

    public function testGetService()
    {
        $this->assertEquals($this->gsSafebrowsing, $this->sut->getService());
    }
}