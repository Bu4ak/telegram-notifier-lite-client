<?php

namespace Bu4ak\TelegramNotifierLite\Test;

use Bu4ak\TelegramNotifierLite\TelegramNotifierLite;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;
use Psr\Log\Test\TestLogger;

final class TelegramNotifierLiteTest extends TestCase
{
    /** @var TelegramNotifierLite */
    private $notifier;
    /** @var TestLogger */
    private $logger;
    private $errorMessage = 'Error communicating with server';
    private $firstToken = 'first_token';
    private $secondToken = 'second_token';

    public function setUp(): void
    {
        parent::setUp();

        $this->logger = new TestLogger();

        $mock = new MockHandler(
            [
                new Response(200, [], 'test'),
                new Response(401),
                new RequestException($this->errorMessage, new Request('POST', 'test'))
            ]
        );

        $handler = HandlerStack::create($mock);
        $client = new Client(['handler' => $handler]);

        $this->notifier = new TelegramNotifierLite($client, '', $this->firstToken, $this->logger);
    }

    /**
     * @test
     */
    public function send(): void
    {
        $this->assertEmpty($this->logger->records);

        $this->notifier->send(['test' => 'data']);
        $this->notifier->send(1, $this->secondToken);
        $this->notifier->send('test');

        $this->assertEmpty($this->logger->records);

        $this->notifier->__destruct();

        $this->assertCount(3, $this->logger->records);

        $this->assertTrue($this->logger->hasRecordThatContains($this->errorMessage, 'error'));
        $this->assertTrue(
            $this->logger->hasRecordThatContains(
                'Client error: `POST ` resulted in a `401 Unauthorized` response',
                'error'
            )
        );
        $this->assertTrue($this->logger->hasRecordThatContains('test', 'info'));
    }
}
