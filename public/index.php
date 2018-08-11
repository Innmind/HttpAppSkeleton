<?php
declare(strict_types = 1);

require __DIR__.'/../vendor/autoload.php';

use Innmind\HttpServer\Main;
use Innmind\Http\Message\{
    ServerRequest,
    Response,
    StatusCode\StatusCode,
};
use Innmind\Compose\ContainerBuilder\ContainerBuilder;
use Innmind\Url\{
    PathInterface,
    Path,
};
use Innmind\HttpFramework\Environment;
use Innmind\Filesystem\Stream\StringStream;
use Innmind\Immutable\{
    MapInterface,
    Set,
};
use Whoops\{
    Run,
    Handler\PrettyPageHandler,
};

new class extends Main
{
    protected function main(ServerRequest $request): Response
    {
        $environment = $this->environment($request);
        $debug = $environment['appEnv'] ?? 'prod' === 'dev';

        try {
            return $this->handle($request, $environment);
        } catch (\Throwable $e) {
            if ($debug) {
                return $this->debug($request, $e);
            }

            throw $e;
        }
    }

    /**
     * @return MapInterface<string, mixed>
     */
    private function environment(ServerRequest $request): MapInterface
    {
        return Environment::camelize(
            __DIR__.'/../config/.env',
            $request->environment()
        )
            ->put('routes', Set::of(
                PathInterface::class,
                new Path(__DIR__.'/../config/routes.yml')
            ))
            ->put('templates', new Path(__DIR__.'/../templates'));
    }

    /**
     * @param MapInterface<string, mixed> $environment
     */
    private function handle(ServerRequest $request, MapInterface $environment): Response
    {
        $container = (new ContainerBuilder)(
            new Path(__DIR__.'/../container.yml'),
            $environment
        );
        $handle = $container->get('requestHandler');

        return $handle($request);
    }

    private function debug(ServerRequest $request, \Throwable $e): Response
    {
        $whoops = new Run;
        $whoops->pushHandler(new PrettyPageHandler);

        return new Response\Response(
            $code = StatusCode::of('INTERNAL_SERVER_ERROR'),
            $code->associatedReasonPhrase(),
            $request->protocolVersion(),
            null,
            new StringStream($whoops->handleException($e))
        );
    }
};
