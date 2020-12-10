<?php

namespace App\Http\Middleware;

use Closure;

class FacebookWebhookMiddleware
{
    private $appsecret;

    public function __construct($appsecret)
    {
        $this->appsecret = $appsecret;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (!$this->isRequestSignatureValid($request)) {
            abort(403, 'Access Denied');
        }

        return $next($request);
    }

    private function isRequestSignatureValid($request)
    {
        $raw_post_data = file_get_contents('php://input');
        $header_signature = $request->header('X-Hub-Signature');

        // Signature matching
        $expected_signature = hash_hmac('sha1', $raw_post_data, $this->appsecret);

        $signature = '';
        if (strlen($header_signature) == 45 && substr($header_signature, 0, 5) == 'sha1=') {
            $signature = substr($header_signature, 5);
        }

        return hash_equals($signature, $expected_signature);
    }
}
