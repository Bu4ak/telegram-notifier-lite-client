<?php

namespace Bu4ak\TelegramNotifierLite;

use GuzzleHttp\ClientInterface;
use GuzzleHttp\Promise\PromiseInterface;
use Psr\Log\LoggerInterface;
use Throwable;
use function GuzzleHttp\Promise\settle;

/**
 * Class TelegramNotifierLiteTest.
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
    private $logger;
    /**
     * @var ClientInterface
     */
    private $httpClient;
    /**
     * @var PromiseInterface[]
     */
    private $promises = [];

    /**
     * @param ClientInterface $httpClient (configured with base_uri)
     * @param LoggerInterface $logger
     * @param string $token
     */
    public function __construct(ClientInterface $httpClient, LoggerInterface $logger, string $token)
    {
        $this->httpClient = $httpClient;
        $this->token = $token;
        $this->logger = $logger;
    }

    public function __destruct()
    {
        settle($this->promises)->wait();
    }

    /**
     * {@inheritdoc}
     */
    public function send($data, string $token = ''): void
    {
        $token = $token ?: $this->token;
        $message = substr($this->encode($data), 0, 4096);

        $promise = $this->httpClient->requestAsync('POST', "api/send/?token=$token&message=$message");
        $promise->then(null, function (Throwable $e) {
            $this->logger->error($e->getMessage(), $e->getTrace());
        });

        $this->promises[] = $promise;
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
