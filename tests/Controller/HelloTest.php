<?php
declare(strict_types = 1);

namespace Tests\AppName\Controller;

use AppName\Controller\Hello;
use Innmind\HttpFramework\Controller;
use Innmind\Http\Message\{
    ServerRequest,
    Response,
};
use Innmind\Router\{
    Route,
    Route\Name,
};
use Innmind\Immutable\{
    Map,
    Str,
};
use PHPUnit\Framework\TestCase;

class HelloTest extends TestCase
{
    public function testInterface()
    {
        $this->assertInstanceOf(
            Controller::class,
            new Hello
        );
    }

    public function testInvokation()
    {
        $response = (new Hello)(
            $this->createMock(ServerRequest::class),
            Route::of(new Name('index'), Str::of('GET /')),
            new Map('string', 'string')
        );

        $this->assertInstanceOf(Response::class, $response);
        $this->assertSame(200, $response->statusCode()->value());
    }
}
