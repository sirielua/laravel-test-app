<?php

namespace App\Components\Sms\Providers\TanzaniaMessaging;

use App\Components\Sms\Sender\Sender;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;

use App\Components\Sms\Sender\Result;
use App\Components\Sms\Exceptions\NoResponseException;

class TanzaniaSender implements Sender
{
    use ResponceFilterTrait;

    const STATUS_PENDING = 1;
    const STATUS_DELIVERED = 3;
    const STATUS_REJECTED = 5;

    private $client;
    private $senderId;
    private $uri = 'text/single';

    public function __construct(Client $client, $senderId)
    {
        $this->client = $client;
        $this->senderId = $senderId;
    }

    public function send($to, $message): Result
    {
        try {
            $response = $this->executeApiCall($to, $message);
            $data = json_decode((string)$response->getBody(), true);
            return $this->formatResponseData($data);
        } catch (ClientException $e) {
            throw $this->formatException($e);
        }
    }

    private function executeApiCall($to, $message)
    {
        $data = ['from' => $this->senderId, 'to' => $to, 'text' => $message];
        return $this->client->post($this->uri, ['json' => $data]);
    }

    private function formatResponseData($data)
    {
        if (!$data) {
            throw new NoResponseException();
        }

        $status = $data['messages'][0]['status'] ?? null;

        if ($status) {
            $id = $status['id'] ?? null;
            $message = $status['groupName'] . ': ' . $status['description'];
            $result = in_array((int)$status['groupId'], [self::STATUS_PENDING, self::STATUS_DELIVERED]);

            return new Result($result, $id, $message);
        } else {
            throw new NoResponseException();
        }
    }
}
