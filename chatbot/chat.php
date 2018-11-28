 <?php
require_once 'vendor/autoload.php';
use BotMan\BotMan\BotMan;
use BotMan\BotMan\BotManFactory;
use BotMan\BotMan\Drivers\DriverManager;
DriverManager::loadDriver(\BotMan\Drivers\Web\WebDriver::class);
$botman = BotManFactory::create([]);
use BotMan\BotMan\Middleware\ApiAi;
$dialogflow = ApiAi::create('ad43cedc0d524a6ca5737bbdd698525f')->listenForAction();
// Apply global "received" middleware
$botman->middleware->received($dialogflow);
// Apply matching middleware per hears command
//$botman->hears('smalltalk.agent.hungry', function (BotMan $bot) { chi muon chat ve Hungry
$botman->hears('.*', function (BotMan $bot) { //chat tat ca chu de
    // The incoming message matched the "my_api_action" on Dialogflow
    // Retrieve Dialogflow information:
    $extras = $bot->getMessage()->getExtras();
    $apiReply = $extras['apiReply'];
    $apiAction = $extras['apiAction'];
    $apiIntent = $extras['apiIntent'];
	if($apiReply != "")
		$bot->reply($apiReply);
	else
		$bot->reply("Thanks!");
})->middleware($dialogflow);
$botman->fallback(function($bot) {
    $bot->reply('Sorry, I did not understand these commands. Please rephare');
});
// Start listening
$botman->listen();