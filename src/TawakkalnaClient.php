<?php

namespace Abather\TawakkalnaMessage;

use Illuminate\Support\Facades\Http;

class TawakkalnaClient
{
    private $client;

    private $token;

    public function __construct()
    {
        $this->client = Http::withHeaders([
            'Content-Type' => ' application/json',
        ])
            ->baseUrl(config('tawakkalna-message.base_url'))
            ->acceptJson();

        $this->setAuthorization();
    }

    public function setAuthorization(): void
    {
        config('tawakkalna-message.authorization_method') === 'bearer' ? $this->setBearerAuth() : $this->setBasicAuth();
    }

    private function setBearerAuth(): void
    {
        $token = config('tawakkalna-message.token');

        if (! $token) {
            throw new \Exception('TawakkalnaMessage Token is not configured');
        }

        $this->client->withToken($token);
    }

    private function setBasicAuth()
    {
        $username = config('tawakkalna-message.username');
        $password = config('tawakkalna-message.password');

        if (! $username || ! $password) {
            throw new \Exception('TawakkalnaMessage Basic Auth is not configured');
        }

        $this->client->withBasicAuth($username, $password);
    }

    public function sendMessage($message, $identifier, $phone = null)
    {
        return $this->client
            ->post('messages', [
                'nationalId' => $identifier,
                'mobileNumber' => $phone,
                'content' => $message,
            ])
            ->json();
    }

    public function messageStatus($id)
    {
        return $this->client
            ->get("messages/$id/status")
            ->json();
    }
}
