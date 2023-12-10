<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Symfony\Component\HttpClient\Chunk\ServerSentEvent;
use Symfony\Component\HttpClient\EventSourceHttpClient;
use Symfony\Component\HttpClient\Exception\JsonException;
use Symfony\Component\HttpClient\HttpClient;

class Qianwen
{
    public function __construct(protected string $apiKey, protected string $secretKey)
    {
    }

    private function getAccessToken(): string
    {
        return Cache::remember('qianwen:access-token', now()->addDays(30), function () {
            $response = Http::withQueryParameters([
                'grant_type' => 'client_credentials',
                'client_id' => $this->apiKey,
                'client_secret' => $this->secretKey,
            ])->post('https://aip.baidubce.com/oauth/2.0/token');
            $response->throw();
            $data = $response->json();
            $response->throwIf($data['error_code'] ?? false);
            $response->throwIf(! $data['access_token']);

            return $data['access_token'];
        });
    }

    /**
     * @throws JsonException
     */
    public function ask(array $messages, ?callable $handlePartial = null, string $model = 'eb-instant'): string
    {
        $token = $this->getAccessToken();

        $client = HttpClient::create();
        $client = new EventSourceHttpClient($client);
        $source = $client->connect("https://aip.baidubce.com/rpc/2.0/ai_custom/v1/wenxinworkshop/chat/$model?access_token=$token", [
            'headers' => [
                'Content-Type: application/json',
            ],
            'body' => json_encode([
                'stream' => true,
                'messages' => $messages,
            ]),
        ], 'POST');
        $result = '';
        while ($source) {
            foreach ($client->stream($source) as $chunk) {
                if ($chunk instanceof ServerSentEvent) {
                    $data = $chunk->getArrayData();
                    $result .= $data['result'];
                    if ($handlePartial) {
                        $handlePartial($data['result']);
                    }
                    if ($data['is_end']) {
                        return $result;
                    }
                }
            }
        }

        return $result;
    }
}
