<?php
declare(strict_types = 1);

require __DIR__.'/../vendor/autoload.php';

use Innmind\HttpServer\Main;
use Innmind\Http\Message\{
    ServerRequest,
    Response,
};
use Innmind\Compose\ContainerBuilder\ContainerBuilder;
use Innmind\Url\{
    PathInterface,
    Path,
};
use Innmind\HttpFramework\Environment;
use Innmind\Immutable\Set;

new class extends Main
{
    protected function main(ServerRequest $request): Response
    {
        $container = (new ContainerBuilder)(
            new Path(__DIR__.'/../container.yml'),
            Environment::camelize(
                __DIR__.'/../config/.env',
                $request->environment()
            )
                ->put(
                    'routes',
                    Set::of(
                        PathInterface::class,
                        new Path(__DIR__.'/../config/routes.yml')
                    )
                )
        );
        $handle = $container->get('requestHandler');

        return $handle($request);
    }
};
