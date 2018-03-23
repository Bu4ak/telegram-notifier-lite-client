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
 * Class TelegramNotifierLite
 * @package Bu4ak\TelegramNotifierLite
 */
class TelegramNotifierLite
{
    /**
     * @var string
     */
    private $token = '';
    /**
     * @var Client
     */
    private $client = null;
    /**
     * @var array
     */
    private $promises = [];

    /**
     * TelegramNotifierLite constructor.
     *
     * @param string $token
     */
    public function __construct(string $token)
    {
        $this->client = new Client(['base_uri' => 'http://185.246.66.87/']);
        $this->token = $token;
    }

    /**
     *
     */
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
        $token = $token ?? $this->token;

        $bt = debug_backtrace()[0];
        $caller = basename($bt['file']).' ('.$bt['line'].')';

        $message = substr("$caller%0A".$this->encode($data), 0, 4096);

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
