<?php

require_once 'vendor/autoload.php';

use GuzzleHttp\Client;

class TelegramBot
{

    protected $apiKey;
    protected $updateId;

    public function __construct($apiKey)
    {
        $this->apiKey = $apiKey;
    }

    public function query($method, $params = [])
    {
        $url = 'https://api.telegram.org/bot';
        $url .= $this->apiKey;
        $url .= '/' . $method;

        if (!empty($params)) {
            $url .= '?' . http_build_query($params);
        }

        $client = new Client();

        $result = $client->request('GET', $url);

        return json_decode($result->getBody());
    }

    public function getUpdates()
    {
        $response = $this->query('getUpdates', [
            'offset' => $this->updateId + 1
        ]);

        if (!empty($response->result)) {
            $this->updateId = $response->result[count($response->result) - 1]->update_id;
        }

        return $response->result;
    }

    public function sendMessage($chatId, $text)
    {
        $responce = $this->query('sendMessage', [
            'text' => $text,
            'chat_id' => $chatId
        ]);

        return $responce;
    }

}