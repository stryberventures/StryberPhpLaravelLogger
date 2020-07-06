<?php

declare(strict_types=1);

namespace Stryber\Logger\Collectors\Response;

use Illuminate\Http\JsonResponse;
use Stryber\Logger\Collectors\CleaningCollector;
use Stryber\Logger\Collectors\Passable;
use Symfony\Component\HttpFoundation\Response;

final class ResponseDataCollector extends CleaningCollector
{
    protected function getRawContext(Passable $passable): array
    {
        if ($passable->input->getStatusCode() >= 400) {
            return $this->resolveResponseData($passable->input);
        }

        return [];
    }

    /**
     * @param Response $response
     * @return false|array|string
     */
    private function resolveResponseData(Response $response): array
    {
        return $response instanceof JsonResponse ?
            $response->getData(true):
            [$response->getContent()];
    }
}
