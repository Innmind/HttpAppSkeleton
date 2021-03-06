<?php
declare(strict_types = 1);

namespace Tests\AppName;

use Innmind\Compose\ContainerBuilder\ContainerBuilder;
use Innmind\Url\{
    PathInterface,
    Path,
    Url,
};
use Innmind\Http\{
    Message\ServerRequest\ServerRequest,
    Message\Response,
    Message\Method\Method,
    ProtocolVersion\ProtocolVersion,
    Headers\Headers,
};
use Innmind\Immutable\{
    Map,
    Set,
};
use Psr\Container\ContainerInterface;
use PHPUnit\Framework\TestCase as BaseTestCase;

class TestCase extends BaseTestCase
{
    private $container;

    public function setUp()
    {
        parent::setUp();

        $this->container = (new ContainerBuilder)(
            new Path(__DIR__.'/../config/container.yml'),
            (new Map('string', 'mixed'))
                ->put('routes', Set::of(
                    PathInterface::class,
                    new Path(__DIR__.'/../config/routes.yml')
                ))
                ->put('templates', new Path(__DIR__.'/../templates'))
                ->put('debug', false)
        );
    }

    protected function container(): ContainerInterface
    {
        return $this->container;
    }

    protected function request(
        string $method,
        string $url,
        array $headers = []
    ): Response {
        return $this->container()->get('requestHandler')(
            new ServerRequest(
                Url::fromString($url),
                new Method(strtoupper($method)),
                new ProtocolVersion(1, 1),
                Headers::of(...$headers)
            )
        );
    }
}
