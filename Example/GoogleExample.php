<?php
use Antevenio\SafeUrl\Provider\Google;

require_once '../vendor/autoload.php';

class GoogleExample {

    protected $developerKey;

    public function __construct($developerKey)
    {
        $this->developerKey = $developerKey;
    }

    public function run()
    {
        $client = new Google_Client();
        $client->setDeveloperKey($this->developerKey);
        $validator = new \Antevenio\SafeUrl\Validator(
            Google::create($client)
        );
        $ret = $validator->validateUrls([
            "http://mandarin.aquamarineku.com/",
            "http://1royalbank-clientsupport.com/",
            "http://www.mdirector.com/"
        ]);
        var_dump($ret);
    }
}

if ($argc < 2)
    die("Please provide a developer key");

$app = new GoogleExample($argv[1]);
$app->run();
