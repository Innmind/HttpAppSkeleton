<?php
declare(strict_types = 1);

namespace AppName\Controller;

use Innmind\HttpFramework\Controller;
use Innmind\Router\Route;
use Innmind\Http\Message\{
    ServerRequest,
    Response,
    StatusCode\StatusCode,
};
use Innmind\Immutable\MapInterface;

final class Hello implements Controller
{
    public function __invoke(
        ServerRequest $request,
        Route $route,
        MapInterface $arguments
    ): Response {
        return new Response\Response(
            $code = StatusCode::of('OK'),
            $code->associatedReasonPhrase(),
            $request->protocolVersion()
        );
    }
}
