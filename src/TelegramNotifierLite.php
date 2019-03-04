<?php
/**
 * Created by PhpStorm.
 * User: Bu4ak
 * Date: 22.03.2018
 * Time: 20:33.
 */

namespace Bu4ak\TelegramNotifierLite;

use GuzzleHttp\Client;
use GuzzleHttp\Promise\PromiseInterface;
use function GuzzleHttp\Promise\settle;

/**
 * Class TelegramNotifierLiteTest.
 */
class TelegramNotifierLite implements TelegramNotifier
{
    /**
     * @var string
     */
    private $token;
    /**
     * @var Client
     */
    private $client;
    /**
     * @var PromiseInterface[]
     */
    private $promises;

    /**
     * TelegramNotifierLiteTest constructor.
     *
     * @param string $baseUri
     * @param string $token
     */
    public function __construct(string $baseUri, string $token)
    {
        $this->client = new Client(['base_uri' => $baseUri]);
        $this->token = $token;
    }

    public function __destruct()
    {
        settle($this->promises)->wait();
    }

    /**
     * @inheritdoc
     */
    public function send($data, string $token = ''): void
    {
        $token ?: $token = $this->token;

        $backtrace = debug_backtrace();
        $caller = basename($backtrace[0]['file']) . ' (' . $backtrace[0]['line'] . ')';
        $message = substr("$caller%0A{$this->encode($data)}", 0, 4096);

        $this->promises[] = $this->client->requestAsync(
            'post',
            "api/send/?token=$token&message=$message"
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
