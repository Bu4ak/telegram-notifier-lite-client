<?php
/**
 * Created by PhpStorm.
 * User: Bu4ak
 * Date: 22.03.2018
 * Time: 20:33
 */

namespace TelegramNotifierLite;

use GuzzleHttp\Client;
use function GuzzleHttp\Promise\settle;

class TelegramNotifierLite
{
    /**
     * @var self
     */
    private static $instance = null;
    /**
     * @var Client
     */
    private $client = null;
    /**
     * @var array
     */
    private $promises = [];

    /**
     * NotifierLite constructor.
     */
    private function __construct()
    {
        $this->client = new Client(['base_uri' => 'http://185.246.66.87/']);
    }

    private function __clone()
    {
    }

    private function __wakeup()
    {
    }

    public function __destruct()
    {
        settle($this->promises)->wait();
    }

    /**
     * @return TelegramNotifierLite
     */
    private static function getInstance(): self
    {
        if (self::$instance == null) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     * @param $data
     */
    public static function send($data, $token = null): void
    {
        $token = $token ?? TELEGRAM_NOTIFIER_LITE_TOKEN;

        $bt = debug_backtrace()[0];
        $caller = basename($bt['file']).' ('.$bt['line'].')';

        $message = substr("$caller%0A".self::toString($data), 0, 4096);

        $instance = self::getInstance();
        $instance->promises[] = $instance->client->requestAsync(
            'post',
            "api/send/?token=$token&message=$message"
        );
    }

    /**
     * @param $data
     *
     * @return string
     */
    private static function toString($data): string
    {
        if (!is_string($data)) {
            $data = json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        }

        return $data;
    }
}
