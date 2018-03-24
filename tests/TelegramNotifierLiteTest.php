<?php

use Bu4ak\TelegramNotifierLite\TelegramNotifierLite;
use PHPUnit\Framework\TestCase;

final class TelegramNotifierLiteTest extends TestCase
{
    private $notifier;
    private $token;
    private $reflectionObject;

    public function __construct($name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);

        $this->token = bin2hex(random_bytes(8));
        $this->notifier = new TelegramNotifierLite($this->token);
        $this->reflectionObject = new ReflectionObject($this->notifier);
    }

    /**
     * @test
     */
    public function clientPropertyIsGuzzleInstance(): void
    {
        $clientProp = $this->reflectionObject->getProperty('client');
        $clientProp->setAccessible(true);

        $this->assertInstanceOf(
            \GuzzleHttp\Client::class,
            $clientProp->getValue($this->notifier)
        );
    }

    /**
     * @test
     */
    public function checkToken(): void
    {
        $tokenProp = $this->reflectionObject->getProperty('token');
        $tokenProp->setAccessible(true);

        $this->assertEquals(
            $this->token,
            $tokenProp->getValue($this->notifier)
        );
    }

    /**
     * @test
     */
    public function encode(): void
    {
        $data = ['hello' => 'world', 123, 'дороу'];
        $encodeMethod = $this->reflectionObject->getMethod('encode');
        $encodeMethod->setAccessible(true);

        $dataEncoded = $encodeMethod->invoke($this->notifier, $data);

        $this->assertTrue(is_string($dataEncoded));

        $this->assertEquals(
            json_encode($data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE),
            $dataEncoded
        );

        $this->assertEquals(
            $encodeMethod->invoke($this->notifier, $dataEncoded),
            $dataEncoded
        );
    }
}
