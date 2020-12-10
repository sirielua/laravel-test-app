<?php

namespace App\Services\Facebook;

use App\domain\service\Participant\LinkFacebook\LinkFacebookCommand;
use App\domain\service\Participant\LinkFacebook\LinkFacebookHandler;

class WebhookHandler
{
    private $token;
    private $pageId;

    public function __construct($token, $pageId)
    {
        $this->token = $token;
        $this->pageId = $pageId;
    }

    public function verify($data)
    {
        if (($data['hub']['mode'] === 'subscribe') && ($data['hub']['verify_token'] === $this->token) && isset($data['hub']['challenge'])) {
            return $data['hub']['challenge'];
        }

        throw new exceptions\InvalidVerificationRequest();
    }

    public function handle($data = [])
    {
        $pageId = $data['entry']['messaging']['recipient']['id'] ?? null;
        if ($pageId !== $this->pageId) {
            throw new \Exception('Invalid <PAGE_ID>');
        }

        if (isset($data['entry']['messaging']['optin'])) {
            return $this->handleOptin($data);
        }
    }

    /* Handlers */

    private function handleOptin(array $data)
    {
        $psid = $data['entry']['messaging']['sender']['id'] ?? null;
        $id = $data['entry']['messaging']['optin']['ref'] ?? null;

        $command = new LinkFacebookCommand($id, $psid);
        $handler = app()->make(LinkFacebookHandler::class);

        $handler->handle($command);
    }
}
