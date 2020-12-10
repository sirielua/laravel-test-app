<?php

/**
 * The Messenger Platform sends events to your webhook to notify you
 * when a variety of interactions or events happen,
 * including when a person sends a message.
 * Webhook events are sent by the Messenger
 * Platform as POST requests to your webhook.
 */
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;

use App\Services\Facebook\WebhookHandler;
use App\Services\Facebook\exceptions\InvalidVerificationRequest;

class FacebookWebhookController extends Controller
{
    public function verify(WebhookHandler $webhook, Request $request)
    {
        try {
            $output = $webhook->verify($request->all());
            return (new Response($output))->setStatusCode(200);
        } catch (InvalidVerificationRequest $e) {
            abort(403, 'Access Denied');
        }
    }

    /**
     * Required 200 OK Response
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(WebhookHandler $webhook, Request $request)
    {
        $webhook->handle($request->all());

        return (new Response())->setStatusCode(200, 'Success');
    }
}
