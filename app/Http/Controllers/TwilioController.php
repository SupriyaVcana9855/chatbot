<?php 
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Twilio\Rest\Client;
use Log;

class TwilioController extends Controller
{
    public function handleIncomingMessage(Request $request)
    {
        $from = $request->input('From');
        $body = $request->input('Body');

        // Log the incoming message for debugging
        Log::info("Received message from $from: $body");

        // Process the message with BotMan
        $responseMessage = $this->processWithBotMan($body, $from);

        // Send response back to WhatsApp
        $this->sendMessageToWhatsApp($from, $responseMessage);

        return response()->json(['status' => 'Message received']);
    }

    protected function processWithBotMan($message, $from)
    {
        // Initialize BotMan
        $config = [];
        DriverManager::loadDriver(\BotMan\Drivers\Web\WebDriver::class);
        $botman = BotManFactory::create($config);

        // Process the message
        $botman->hears('*', function (BotMan $bot) use ($message) {
            $bot->reply("Received: " . $message);
        });

        // Listen to BotMan
        $botman->hears($message, function (BotMan $bot) use ($message) {
            $bot->reply($message); // Echo back the message
        });

        $botman->listen();

        // Fetch BotMan's response (modify according to your setup)
        return 'Response from BotMan';
    }

    protected function sendMessageToWhatsApp($to, $message)
    {
        $twilioSid = env('TWILIO_SID');
        $twilioAuthToken = env('TWILIO_AUTH_TOKEN');
        $twilioFrom = env('TWILIO_WHATSAPP_FROM');

        $client = new Client($twilioSid, $twilioAuthToken);

        $client->messages->create(
            $to,
            [
                'from' => $twilioFrom,
                'body' => $message
            ]
        );
    }
}
