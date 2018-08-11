<?php
declare(strict_types = 1);

namespace Tests\AppName;

use Innmind\Compose\ContainerBuilder\ContainerBuilder;
use Innmind\Url\{
    PathInterface,
    Path,
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
            new Path(__DIR__.'/../container.yml'),
            (new Map('string', 'mixed'))
                ->put('routes', Set::of(
                    PathInterface::class,
                    new Path(__DIR__.'/../config/routes.yml')
                ))
                ->put('templates', new Path(__DIR__.'/../templates'))
        );
    }

    protected function container(): ContainerInterface
    {
        return $this->container;
    }
}
