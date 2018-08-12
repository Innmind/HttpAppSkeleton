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
use Innmind\Immutable\{
    MapInterface,
    Set,
};

new class extends Main
{
    protected function main(ServerRequest $request): Response
    {
        $environment = $this->environment($request);

        return $this->handle($request, $environment);
    }

    /**
     * @return MapInterface<string, mixed>
     */
    private function environment(ServerRequest $request): MapInterface
    {
        $environment = Environment::camelize(
            __DIR__.'/../config/.env',
            $request->environment()
        );

        return $environment
            ->put('routes', Set::of(
                PathInterface::class,
                new Path(__DIR__.'/../config/routes.yml')
            ))
            ->put('templates', new Path(__DIR__.'/../templates'))
            ->put('debug', ($environment['appEnv'] ?? 'prod') === 'dev');
    }

    /**
     * @param MapInterface<string, mixed> $environment
     */
    private function handle(ServerRequest $request, MapInterface $environment): Response
    {
        $container = (new ContainerBuilder)(
            new Path(__DIR__.'/../config/container.yml'),
            $environment
        );
        $handle = $container->get('requestHandler');

        return $handle($request);
    }
};
