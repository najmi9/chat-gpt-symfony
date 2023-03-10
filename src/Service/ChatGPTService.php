<?php

declare(strict_types=1);

namespace App\Service;

use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use UnexpectedValueException;

class ChatGPTService
{
    private string $botEndpoint;

    private string $botSecretKey;

    public function __construct(
        private HttpClientInterface $httpClient,
        ParameterBagInterface $parameterBag
    ) {
        $this->botEndpoint = $parameterBag->get('chat_gpt_endpoint');
        $this->botSecretKey = $parameterBag->get('chat_gpt_secret_key');
    }

    public function getAnswerFromBot(
        ?string $question = null
    ): string {
        if (null === $question) {
            return '';
        }
        $response = $this->httpClient->request(
            Request::METHOD_POST,
            $this->botEndpoint,
            [
            'headers' => [
                'Authorization' => "Bearer {$this->botSecretKey}",
            ],
            'json' => [
                'prompt' => $question,
                'max_tokens' => 100,
                'temperature' => 0.9,
                'model' => 'text-davinci-003',
            ],
        ]);

        $responseData = $response->toArray();

        $answer = $responseData['choices'][0]['text'] ?? null;

        if (null === $answer) {
            throw new UnexpectedValueException('Unable to get response from bot');
        }

       return $answer;
    }
}
