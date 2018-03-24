<?php

use Bu4ak\TelegramNotifierLite\TelegramNotifierLite;
use PHPUnit\Framework\TestCase;

final class TelegramNotifierLiteTest extends TestCase
{
    public function testClientProperty(): void
    {
        $notifierLite = new TelegramNotifierLite('xxxxxxxxxx');
        $reflectionObject = new ReflectionObject($notifierLite);

        $clientProp = $reflectionObject->getProperty('client');
        $clientProp->setAccessible(true);

        $this->assertInstanceOf(
            \GuzzleHttp\Client::class,
            $clientProp->getValue($notifierLite)
        );
    }
}