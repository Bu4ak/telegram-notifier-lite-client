<?php

use Bu4ak\TelegramNotifierLite\TelegramNotifierLite;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\RequestInterface;
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
    private $requests = [];

    public function setUp()
    {
        parent::setUp();

        $this->logger = new TestLogger();

        $mock = new MockHandler([
            new Response(200),
            new Response(401),
            new RequestException($this->errorMessage, new Request('POST', 'test'))
        ]);

        $handler = HandlerStack::create($mock);
        $handler->push(Middleware::mapRequest(function (RequestInterface $request) {
            parse_str($request->getUri()->getQuery(), $parameters);
            $this->requests[] = $parameters;
            return $request;
        }));

        $client = new Client(['handler' => $handler]);

        $this->notifier = new TelegramNotifierLite($client, $this->logger, $this->firstToken);
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

        $this->assertCount(2, $this->logger->records);

        $this->assertTrue($this->logger->hasRecordThatContains($this->errorMessage, 'error'));

        $this->assertEquals(json_encode(['test' => 'data']), $this->requests[0]['message']);
        $this->assertEquals($this->firstToken, $this->requests[0]['token']);

        $this->assertEquals(1, $this->requests[1]['message']);
        $this->assertEquals($this->secondToken, $this->requests[1]['token']);

        $this->assertEquals('test', $this->requests[2]['message']);
        $this->assertEquals($this->firstToken, $this->requests[2]['token']);
    }
}
