<?php
namespace App\Http\Controllers;
use BotMan\BotMan\BotMan;
use BotMan\BotMan\Drivers\DriverManager;
use BotMan\BotMan\BotManFactory;
use Illuminate\Http\Request;
use Twilio\Rest\Client; 
class BotmanController extends Controller
{
    public function handle()
    {
        $config = [];
    
        DriverManager::loadDriver(\BotMan\Drivers\Web\WebDriver::class);
    
        $botman = BotManFactory::create($config);
    
        $botman->hears('hello', function (BotMan $bot) {
            $bot->reply('Hello!');
        });
    
        $botman->fallback(function (BotMan $bot) {
            $userMessage = $bot->getMessage()->getText();
    
            // Notify customer service
            $this->notifyCustomerService($userMessage);
    
            // Reply to the user
            $bot->reply("I'm not sure how to respond to that. Please wait while I connect you to a customer service representative.");
        });
    
        $botman->listen();
    }
    

    protected function notifyCustomerService($message)
    {
        $twilioSid = env('TWILIO_SID');
        $twilioAuthToken = env('TWILIO_AUTH_TOKEN');
        $twilioFrom = env('TWILIO_WHATSAPP_FROM');
        $twilioTo = env('TWILIO_WHATSAPP_TO');

        $client = new Client($twilioSid, $twilioAuthToken);

        $client->messages->create(
            $twilioTo,
            [
                'from' => $twilioFrom,
                'body' => "User sent the following message: $message"
            ]
        );
    }
}
