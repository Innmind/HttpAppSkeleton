<?php
declare(strict_types = 1);

require __DIR__.'/../vendor/autoload.php';

use Innmind\HttpServer\Main;
use Innmind\Http\Message\{
    ServerRequest,
    Response,
};

new class extends Main
{
    protected function main(ServerRequest $request): Response
    {

    }
};
