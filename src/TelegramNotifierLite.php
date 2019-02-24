<?php
/**
 * Created by PhpStorm.
 * User: Bu4ak
 * Date: 22.03.2018
 * Time: 20:33.
 */

namespace Bu4ak\TelegramNotifierLite;

use GuzzleHttp\Client;
use function GuzzleHttp\Promise\settle;

/**
 * Class TelegramNotifierLiteTest.
 */
class TelegramNotifierLite
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
     * @var array
     */
    private $promises = [];

    /**
     * TelegramNotifierLiteTest constructor.
     *
     * @param string $token
     */
    public function __construct(string $token)
    {
        $this->client = new Client(['base_uri' => config('notifier_lite.api_base_url')]);
        $this->token = $token;
    }

    public function __destruct()
    {
        settle($this->promises)->wait();
    }

    /**
     * @param      $data
     * @param null $token
     */
    public function send($data, $token = null): void
    {
        $token ?: $token = $this->token;

        $backtrace = debug_backtrace();
        $caller = basename($backtrace[0]['file']).' ('.$backtrace[0]['line'].')';
        $message = substr("$caller%0A{$this->encode($data)}", 0, 4096);

        $this->promises[] = $this->client->requestAsync(
            'post',
            "api/send/?token=$token&message=$message"
        );
    }

    /**
     * @param $data
     *
     * @return string
     */
    private function encode($data): string
    {
        if (!is_string($data)) {
            $data = json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        }

        return $data;
    }
}
