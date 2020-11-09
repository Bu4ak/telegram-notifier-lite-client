<?php

namespace Bu4ak\TelegramNotifierLite;

use GuzzleHttp\ClientInterface;
use GuzzleHttp\Promise\PromiseInterface;
use GuzzleHttp\Promise\Utils;
use GuzzleHttp\Psr7\Response;
use Psr\Log\LoggerInterface;

/**
 * Class TelegramNotifierLite.
 */
final class TelegramNotifierLite implements TelegramNotifier
{
    /**
     * @var string
     */
    private $token;

    /**
     * @var LoggerInterface
     */
    private static $logger;

    /**
     * @var string
     */
    private $endpoint;

    /**
     * @var ClientInterface
     */
    private $httpClient;

    /**
     * @var PromiseInterface[]
     */
    private $promises = [];

    /**
     * @param ClientInterface $httpClient
     * @param string $endpoint
     * @param string $token
     * @param LoggerInterface|null $logger
     */
    public function __construct(
        ClientInterface $httpClient,
        string $endpoint,
        string $token,
        ?LoggerInterface $logger = null
    ) {
        $this->httpClient = $httpClient;
        $this->endpoint = $endpoint;
        $this->token = $token;
        static::$logger = $logger;
    }

    public function __destruct()
    {
        Utils::settle($this->promises)->wait();
    }

    /**
     * {@inheritdoc}
     */
    public function send($data, string $token = ''): void
    {
        $token = $token ?: $this->token;

        $promise = $this->httpClient->requestAsync(
            'POST',
            $this->endpoint,
            [
                'form_params' => [
                    'token' => $token,
                    'message' => $this->encode($data),
                ]
            ]
        );

        $this->promises[] = $promise;

        if (static::$logger === null) {
            return;
        }
        $promise->then(
            static function (Response $response) {
                static::$logger->info($response->getBody());
            },
            static function (\Throwable $e) {
                static::$logger->error($e->getMessage(), $e->getTrace());
            }
        );
    }

    /**
     * @param mixed $data
     *
     * @return string
     */
    private function encode($data): string
    {
        if (!is_string($data)) {
            return json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        }

        return $data;
    }
}
