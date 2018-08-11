<?php
declare(strict_types = 1);

namespace Tests\AppName\Controller;

use AppName\Controller\Hello;
use Innmind\HttpFramework\Controller;
use Innmind\Http\Message\Response;
use Innmind\Templating\Engine;
use Tests\AppName\TestCase;

class HelloTest extends TestCase
{
    public function testInterface()
    {
        $this->assertInstanceOf(
            Controller::class,
            new Hello(
                $this->createMock(Engine::class)
            )
        );
    }

    public function testIndex()
    {
        $response = $this->request('get', '/');

        $this->assertInstanceOf(Response::class, $response);
        $this->assertSame(200, $response->statusCode()->value());
        $this->assertSame("Hello world!\n", (string) $response->body());
    }

    public function testHello()
    {
        $response = $this->request('get', '/hello/foo%20bar');

        $this->assertInstanceOf(Response::class, $response);
        $this->assertSame(200, $response->statusCode()->value());
        $this->assertSame("Hello foo bar!\n", (string) $response->body());
    }
}
