<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;

class ChatController extends Controller
{
    protected $openAIKey;
    protected $openAIEndpoint;

    public function __construct()
    {
        $this->openAIKey = config('services.openai.secret');
        $this->openAIEndpoint = 'https://api.openai.com/v1/completions';
    }

    public function index()
    {
        return inertia('Chat/Index');
    }

    public function chat(Request $request)
    {
        $client = new Client();

        try {
            $response = $client->post('https://api.openai.com/v1/completions', [
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Authorization' => 'Bearer ' . $this->openAIKey,
                ],
                'json' => [
                    'model' => 'gpt-4o-mini', // yoki kerakli model identifikatori
                    'prompt' => $request->input('prompt'),
                    'max_tokens' => 150,
                    'temperature' => 0.7,
                    'stop' => ['\n']
                ],
            ]);

            return response()->json(json_decode($response->getBody()->getContents(), true));
        } catch (\GuzzleHttp\Exception\ClientException $e) {
            return response()->json([
                'error' => $e->getResponse()->getBody()->getContents(),
            ], $e->getResponse()->getStatusCode());
        }
    }
}
