<?php
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../TelegramBot.php';

use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(dirname(__DIR__));
$dotenv->load();

$apiKey = getenv('API_KEY');

$telegramBot = new TelegramBot($apiKey);
while (true) {
    sleep(2);

    $updates = $telegramBot->getUpdates();

    foreach ($updates as $update) {
        if ($update->message->text == '/help') {
            $telegramBot->sendMessage(
                $update->message->chat->id,
                'type country code from https://en.wikipedia.org/wiki/ISO_3166-1_alpha-2#Officially_assigned_code_elements'
            );
        } else {
            try {
                $country = country($update->message->text);
                $text = $country->getEmoji();
                $text .= "\nhttps://en.wikipedia.org/wiki/" . str_replace(' ', '_', $country->getName());

            } catch (\Exception $e) {
                $text = 'wrong country! type /help for info';
            }
            $telegramBot->sendMessage($update->message->chat->id, "$text");
        }
    }
}
