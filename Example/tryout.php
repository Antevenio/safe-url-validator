<?php
use Antevenio\SafeUrl\Provider\Google;

require_once '../vendor/autoload.php';

class Tryout {
    public function run()
    {
        $client = new Google_Client();
        $client->setDeveloperKey("AIzaSyCHrRaWSkO9s7eX5YdyMrh4MIXjH1LCQQ8");
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

$app = new Tryout();
$app->run();
