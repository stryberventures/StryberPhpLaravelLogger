<?php

declare(strict_types=1);

namespace Stryber\Logger;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Pipeline\Pipeline;
use Psr\Log\LoggerInterface;
use Stryber\Logger\Collectors\Passable;
use Symfony\Component\HttpFoundation\Response;

final class LoggerMiddleware
{
    private Pipeline $pipeline;
    private LoggerInterface $log;
    private array $requestCollectors;
    private array $responseCollectors;

    public function __construct(
        Pipeline $pipeline,
        LoggerInterface $log,
        array $requestCollectors,
        array $responseCollectors
    ) {
        $this->pipeline = $pipeline;
        $this->log = $log;
        $this->requestCollectors = $requestCollectors;
        $this->responseCollectors = $responseCollectors;
    }

    public function handle(Request $request, Closure $next)
    {
        return $next($request);
    }

    public function terminate(Request $request, Response $response): void
    {
        $context = [
            'request' => $this->getRequestContext($request),
            'response' => $this->getResponseContext($response),
        ];

        $response->getStatusCode() < 400 ?
            $this->log->info("", $context):
            $this->log->error("", $context);
    }

    private function getRequestContext(Request $request): array
    {
        return $this->pass(new Passable($request, []), $this->requestCollectors);
    }

    private function getResponseContext(Response $response): array
    {
        return $this->pass(new Passable($response, []), $this->responseCollectors);
    }

    private function pass(Passable $passable, array $pipes): array
    {
        /** @var Passable $passed */
        $passed = $this->pipeline->send($passable)
            ->through($pipes)
            ->thenReturn();

        return $passed->output;
    }
}
