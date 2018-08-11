<?php
declare(strict_types = 1);

namespace Tests\AppName\Controller;

use AppName\Controller\Hello;
use Innmind\HttpFramework\Controller;
use Innmind\Http\{
    Message\ServerRequest\ServerRequest,
    Message\Response,
    Message\Method\Method,
    ProtocolVersion\ProtocolVersion,
};
use Innmind\Url\Url;
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
        $handle = $this->container()->get('requestHandler');
        $request = new ServerRequest(
            Url::fromString('/'),
            Method::get(),
            new ProtocolVersion(1, 1)
        );

        $response = $handle($request);

        $this->assertInstanceOf(Response::class, $response);
        $this->assertSame(200, $response->statusCode()->value());
        $this->assertSame("Hello world!\n", (string) $response->body());
    }

    public function testHello()
    {
        $handle = $this->container()->get('requestHandler');
        $request = new ServerRequest(
            Url::fromString('/hello/foo%20bar'),
            Method::get(),
            new ProtocolVersion(1, 1)
        );

        $response = $handle($request);

        $this->assertInstanceOf(Response::class, $response);
        $this->assertSame(200, $response->statusCode()->value());
        $this->assertSame("Hello foo bar!\n", (string) $response->body());
    }
}
