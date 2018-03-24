<?php

use Bu4ak\TelegramNotifierLite\TelegramNotifierLite;
use PHPUnit\Framework\TestCase;

final class TelegramNotifierLiteTest extends TestCase
{
    private $notifier,$token;

    public function __construct($name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
        $this->notifier = new TelegramNotifierLite('xxxxxxxxxx');

    }

    /**
     * @test
     */
    public function clientPropertyIsGuzzleInstance(): void
    {
        $reflectionObject = new ReflectionObject($this->notifier);

        $clientProp = $reflectionObject->getProperty('client');
        $clientProp->setAccessible(true);

        $this->assertInstanceOf(
            \GuzzleHttp\Client::class,
            $clientProp->getValue($this->notifier)
        );
    }
}